<?

/*******************

FlatGallery was written by Ben Craton (Tsunami.No.Ai)
http://www.falseblue.com

This code is licenced under the GPL and may not be
redistributed in any altered form without the author's
express consent. The author is also not liable for any
damage this code may, or may not, cause. 

See the README file that came with your archive
for more information.

********************/

class flatgallery {

	private $__gallery = "";
	private $__offset = 0;
	private $__cols = 5;
	private $__rows = 50;
	private $__title = "My FlatGallery";
	private $__stylesheet = "./style.css";
	private $__image_dir = "./images";
	private $__dir_list = array();
	private $__thumb_dir="./thumbs";
	private $__thumb_list = array();
	private $__thumb_max_size = 100;
	private $__show_filename = 0;
	private $__show_filesize = 0;
	private $__show_comments = 0;
	private $__link_requisit = "";
	private $__dir_index = array();
	private $__thumb_dir_index = array();
	private $__thumb_index = array();
	private $__image_index = array();
	private $__folder_icon = "";
	private $time_start = 0;
	private $time_end = 0;
	private $error = "";
	private $__size = 0;
	private $__list_size = 0;
	private $__break = 0;
	private $__bump = 0;
	protected $__VERSION="3.0a";
	protected $__COPY="2006";

	function __construct(){
		//ini_set('error_reporting',0);
		ini_set('max_execution_time',300);
		$this->time_start = $this->microtime_float();
		$this->set_offset($_GET['offset']);
		$this->set_gallery(rawurldecode($_GET['gallery']));
		if(empty($this->__gallery)){
			$this->__gallery="";
		}else{
			$this->__gallery=stripslashes(ereg_replace("@","/",$this->__gallery))."";
		}
		if(!is_dir($this->__image_dir."/".$this->__gallery)){
			$this->error="No gallery by name: ".ereg_replace("_"," ",$this->__gallery);
			return false;
		}
		$this->do_parse();
		$this->__bump=sizeof($this->__dir_index);
		$this->__size=$this->__cols*$this->__rows;
		$this->__list_size=sizeof($this->__image_index)+sizeof($this->__dir_index);
		$this->__break=sizeof($this->__image_index);
		if($this->__list_size==0){
			$this->error="No images to display";
		}

	}
	
	private function microtime_float()
	{
	   list($usec, $sec) = explode(" ", microtime());
	   return ((float)$usec + (float)$sec);
	}
	
	protected function do_parse(){
		if($this->checkR($this->__image_dir)===false){
			return false;
		}
		if($this->checkW($this->__thumb_dir)===false){
			return false;
		}
		if($this->__gallery!="")
			$gal=$this->__gallery;
		else
			$gal="";
		$this->__dir_list=$this->list_dir($this->__image_dir."/".$gal);
		//echo "<pre>";
		//print_r($this->__dir_list);
		foreach($this->__dir_list as $foo){
			//echo $this->__image_dir."/".$gal."/".$foo."<br/>";
			if(is_dir($this->__image_dir."/".$gal.$foo)){
				$this->__dir_index[$foo]=$this->list_dir($this->__image_dir."/".$gal.$foo);
				//echo $foo."<br/>";
			}
			if(!is_dir($this->__image_dir."/".$gal.$foo) && preg_match("/\.(jpg|JPG)$|\.(gif|GIF)$|\.(png|PNG)$|\.(jpeg|JPEG)$/",$foo)==1){
				$this->__image_index[]=$foo;
			}
		
		}
		
		$this->__thumb_list=$this->list_dir($this->__thumb_dir."/".$gal);
		foreach($this->__thumb_list as $foo){
			if(is_dir($this->__thumb_dir."/".$gal.$foo)){
				$this->__thumb_dir_index[$foo]=$this->list_dir($this->__thumb_dir."/".$gal.$foo);
			}
			if(preg_match("/\.(jpg|JPG)$|\.(gif|GIF)$|\.(png|PNG)$|\.(jpeg|JPEG)$/",$foo)==1){
				$this->__thumb_index[]=$foo;
			}
		}
		$this->parse_dir($this->__dir_list);
	}
	
	protected function list_dir($d){
		$dir=opendir($d);
		$file_list=array();
		$dir_list=array();
		while($sz = readdir($dir)){
			if($sz !="." && $sz!=".."){
				if(preg_match("/\.(jpg|JPG)$|\.(gif|GIF)$|\.(png|PNG)$|\.(jpeg|JPEG)$/",$d."/".$sz)){
					$file_list[]=$sz;
				}elseif(is_dir($d."/".$sz)){
					$dir_list[]=$sz;
				}
			}
		}
		asort($file_list);
		asort($dir_list);
		$a=array_merge($dir_list,$file_list);
		return $a;
	}

	
	protected function malloc(){
		$size=sizeof($this->__image_index);
		if(sizeof($this->__dir_index)!=0){
			foreach($this->__dir_index as $foo=>$bar){
				$size+=sizeof($bar);
			}
		}
		if($size<20){$size=20;}
		ini_set("memory_limit",$size."M");
		ini_set("max_execution_time",$size);
	}
	
	private function checkR($dir){
		if(!is_dir($dir) || !is_readable($dir)){
			$this->error=$dir." does not exist or is not readable";
			return false;
		}
	}
	private function checkW($dir){
		if(!is_dir($dir) || !is_writable($dir)){
			$this->error=$dir." does not exist or is not writable";
			return false;
		}
	}
	
	public function set_title($intitle){
		$this->__title=$intitle;
	}
	
	public function show_title(){
		echo $this->__title;
	}
	
	public function set_style($stylesheet){
		$this->__stylesheet=$stylesheet;
	}
	
	public function set_dir($dir){
		$this->__image_dir=$dir;
	}
	
	public function set_thumb_dir($dir){
		$this->__thumb_dir=$dir;
	}
	
	public function set_thumb_size($size){
		$this->__thumb_max_size=$size;
	}
	
	public function set_gallery($gal){
		$this->__gallery=$gal;
	}
	
	public function set_folder_icon($icon){
		$this->__folder_icon=$icon;
	}
	
	public function set_cols($cols){
		$this->__cols=$cols;
	}
	
	public function set_rows($rows){
		$this->__rows=$rows;
	}
	
	private function set_offset($offset){
		if(empty($offset) || $offset<0){$offset=0;}
		$this->__offset=$offset;
	}
	
	public function set_show_filename($foo){
		if($foo!=1 && $foo!=0){ $foo=0; }
		$this->__show_filename=$foo;
	}
	
	public function set_show_filesize($foo){
		if($foo!=1 && $foo!=0){ $foo=0; }
		$this->__show_filesize=$foo;
	}

	public function set_show_comments($foo){
		if($foo!=1 && $foo!=0){ $foo=0; }
		$this->__show_comments=$foo;
	}
	
	public function set_link_requisit($link){
		$this->__link_requisit=rawurlencode($link);
	}
	
	protected function image_list($directory){
		$fl=array();
		if($hd=opendir($directory)){
			while ($sz = readdir($hd)) { 
				if (preg_match("/\.(jpg|JPG)$|\.(gif|GIF)$|\.(png|PNG)$|\.(jpeg|JPEG)$/",$sz)==1) {
					$fl[] = $sz; 
				}
			}
		}
		closedir($hd);
		asort($fl);
		return $fl;		
	}
	
	protected function make_thumb($filename){
		$this->malloc();
		if($this->__gallery!="")
			$gal=$this->__gallery;
		else
			$gal="";
		$filename=$gal.$filename;
		$type=exif_imagetype($this->__image_dir."/".$filename);
		print "creating thumbnail for: ".$this->__image_dir."/".$filename."\n";
		switch($type){
			case 1:	$im=imagecreatefromgif($this->__image_dir."/".$filename);					
						break;
			case 2: $im=imagecreatefromjpeg($this->__image_dir."/".$filename);
						break;
			case 3: $im=imagecreatefrompng($this->__image_dir."/".$filename);
						break;
			case 15: $im=imagecreatefromwbmp($this->__image_dir."/".$filename);
						break;
			default: echo $this->__dir."/".$filename." is not JPEG, GIF, or PNG (error: type ".$type.")";
						break;
		}
		if(!$im){
			$this->error="GD error. Image cannot be created.";
			return false;
		}
		$im_x=imagesx($im);
		$im_y=imagesy($im);
		if($im_x>$im_y){
			$width=$this->__thumb_max_size;
			$length=floor($width*$im_y/$im_x);
		}else{
			$length=$this->__thumb_max_size;
			$width=floor($length*$im_x/$im_y);
		}
		$thumb=imagecreatetruecolor($width,$length);
		imagecopyresampled($thumb,$im,0,0,0,0,$width,$length,$im_x,$im_y);
		
		switch($type){
			case 1:	imagegif($thumb,$this->__thumb_dir."/".$filename);
						break;
			case 2: imagejpeg($thumb,$this->__thumb_dir."/".$filename);
						break;
			case 3: imagepng($thumb,$this->__thumb_dir."/".$filename);
						break;
			case 15: imagewbmp($thumb,$this->__thumb_dir."/".$filename);
						break;
		}
		imagedestroy($im);
		imagedestroy($thumb);
		unset($width,$im_x,$im_y,$length,$type);
		return true;
	}
	
	protected function parse_dir(){
		if($this->__gallery!="")
			$gal=$this->__gallery;
		else
			$gal="";
		if(sizeof($this->__image_index)!=0){
			foreach($this->__image_index as $filename){
				if(!file_exists($this->__thumb_dir."/".$gal.$filename)){
					if(!$this->make_thumb($filename))
						return false;
					chmod($this->__thumb_dir."/".$gal.$filename,0777);
				}
			
			}
		}
		if(sizeof($this->__dir_index)!=0){
			foreach($this->__dir_index as $key=>$value){
				if(!file_exists($this->__thumb_dir."/".$gal.$key)){
					mkdir($this->__thumb_dir."/".$gal.$key);
					chmod($this->__thumb_dir."/".$gal.$key,0777);
				}
				foreach($value as $foo){
					if(!file_exists($this->__thumb_dir."/".$gal.$key."/".$foo)){
						$filename=$key."/".$foo;
						if(is_file($this->__image_dir."/".$gal.$key."/".$foo))
							if(!$this->make_thumb($filename))
								return false;
						elseif(is_dir($this->__image_dir."/".$gal.$key."/".$foo))
							mkdir($this->__thumb_dir."/".$gal.$filename);
						chmod($this->__thumb_dir."/".$gal.$filename,0777);
					}
				}
			}
		}
		if(sizeof($this->__thumb_index)!=0){
			foreach($this->__thumb_index as $filename){
				if(!file_exists($this->__image_dir."/".$gal.$filename)){
					unlink($this->__thumb_dir."/".$gal.$filename);
				}
			}
		}
		if(sizeof($this->__thumb_dir_index)!=0){
			foreach($this->__thumb_dir_index as $key=>$value){
				if(!is_dir($this->__image_dir."/".$gal.$key)){
					$this->recursive_del($this->__thumb_dir."/".$gal.$key);
				}else{
					foreach($value as $foo){
						if(!file_exists($this->__image_dir."/".$gal.$key."/".$foo)){
							if(is_file($this->__thumb_dir."/".$gal.$key."/".$foo))
								unlink($this->__thumb_dir."/".$gal.$key."/".$foo);
							elseif(is_dir($this->__thumb_dir."/".$gal.$key."/".$foo))
								$this->recursive_del($this->__thumb_dir."/".$gal.$key."/".$foo);
						}
					}
				}
			}
		}

	}

	protected function recursive_del($dirname)
	{ 
	  if(is_dir($dirname))
	  	$dir_handle=opendir($dirname);
	  while($file=readdir($dir_handle))
	  {
			if($file!="." && $file!="..")
			{                   
			if(!is_dir($dirname."/".$file))
				unlink ($dirname."/".$file);
            else 
				$this->recursive_del($dirname."/".$file);
			}             
		}        
		closedir($dir_handle);         
		rmdir($dirname);          
		return true;
	}       
      
	public function draw_table($img = "./folder.png"){
		if($this->error!=""){
			$this->get_error();
			return false;
		}
			
		$i=1;
		echo "<table class=\"fg_display_table\">\n";
		echo "<tr>";
		$this->__bump=0;
		if($this->__offset<sizeof($this->__dir_index) && sizeof($this->__dir_index)!=0){
			for($j=$this->__offset;$j<($this->__size+$this->__offset);$j++){
				$keys=array_keys($this->__dir_index);
				$gal=ereg_replace("/","@",$this->__gallery);
				if(is_file($img))
					$link="<img src=\"".$img."\" border=\"0\" alt=\"".ereg_replace("_"," ",$keys[$j])."\" height=\"".$this->__thumb_max_size."\" width=\"".$this->__thumb_max_size."\" title=\"".ereg_replace("_"," ",$keys[$j])."\" />";
				else
					$link=ereg_replace("_"," ",$keys[$j]);
				echo "<td class=\"fg_cell\"><a href=\"?".$this->__link_requisit."&amp;gallery=".urlencode($gal.$keys[$j]."/")."\">".$link."</a>\n";
				echo "<br /><span class=\"fg_filename\">".ereg_replace("_"," ",$keys[$j])."<br/>(".sizeof($this->__dir_index[$keys[$j]]).")</span>\n";
				echo "</td>\n";

				if(($this->__bump+1)%$this->__cols==0){
					echo "</tr><tr>\n";
				}
				$i++;
				$this->__bump++;
				//echo "$j - ".sizeof($this->__dir_list)."<br>";
				if($j+1==$break || $j+1+sizeof($this->__image_index)>=sizeof($this->__dir_list)){
					break;
				}
			}
		}
		if(sizeof($this->__image_index)==0 && empty($this->__gallery)){			
			$this->time_end = $this->microtime_float();
			$time =round( $this->time_end - $this->time_start,4);

			echo "<tr><td colspan=\"".$this->__cols."\" class=\"fg_midnav\" style=\"text-align:center;font-size:12px;\">Generated in $time seconds<br/>Powered by <a href=\"http://flatgallery.sourceforge.net\" class=\"fg_link\">FlatGallery</a> ".$this->__VERSION." &copy; 2005-".$this->__COPY." Ben Craton</td></tr></table>\n";
			$this->error="";
			return false;
		}
		if($this->__offset-sizeof($this->__dir_index)-$this->__bump<0)
			$start=0;
		else
			$start=$this->__offset-sizeof($this->__dir_index);

		for($j=$start;$j<($this->__size+$start-$this->__bump);$j++){
			if(!$this->__image_index[$j])
				break;
			$filename=$this->__image_index[$j];
			
			list($width,$height)=getimagesize($this->__thumb_dir."/".$this->__gallery.$filename);
			echo "<td class=\"fg_cell\"><a href=\"".$this->__image_dir."/".$this->__gallery.$filename."\"><img src=\"".$this->__thumb_dir."/".$this->__gallery.$filename."\" class=\"fg_thumb\" alt=\"".$filename."\" title=\"".$filename."\" height=\"".$height."\" width=\"".$width."\" /></a>\n";
			if($this->__show_filename==1){
				echo "<br /><span class=\"fg_filename\">".substr($filename,0,strrpos($filename,"."))."</span>\n";
			}
			if($this->__show_filesize==1){
				echo "<br /><span class=\"fg_filesize\">".$this->show_size($this->__image_dir."/".$this->__gallery.$filename)."</span>\n";
			}
			echo "</td>\n";
			if($j+1==$break){
				break;
			}
			
			if($i % $this->__cols==0 && $i<$this->__size){
				echo "</tr><tr>\n";
			}
			$i++;
		}
		echo "</tr>\n";
		$this->time_end = $this->microtime_float();
		$time =round( $this->time_end - $this->time_start,4);

		echo "<tr><td colspan=\"".$this->__cols."\" class=\"fg_midnav\" style=\"text-align:center;font-size:12px;\">Generated in $time seconds.<br/>Powered by <a href=\"http://flatgallery.sourceforge.net\" class=\"fg_link\">FlatGallery</a> ".$this->__VERSION." &copy; 2005-".$this->__COPY." Ben Craton</td></tr>";
		echo "</table>\n";
	
	}
	
	public function prev_page($img = "./a-prev.png"){
		if($this->__offset!=0){
			if(is_file($img)){
				list($width,$height)=getimagesize($img);
				$link="<img src=\"".$img."\" border=\"0\" alt=\"Prev. Page\" title=\"Prev. Page\"  height=\"".$height."\" width=\"".$width."\" />";
			}else
				$link="&lt; Prev. Page";
			echo "<a href=\"?".$this->__link_requisit."&amp;offset=".($this->__offset-$this->__size)."&amp;gallery=".rawurlencode($this->__gallery)."\" class=\"fg_link\">".$link."</a>";
		}else{
			echo "&nbsp;";
		}
	}
	
	public function next_page($img = "./a-next.png"){
		if(($this->__size+$this->__offset)<$this->__list_size){
			if(is_file($img)){
				list($width,$height)=getimagesize($img);
				$link="<img src=\"".$img."\" border=\"0\" alt=\"Next Page\" title=\"Next Page\" height=\"".$height."\" width=\"".$width."\" />";
			}else
				$link="Next Page &gt;";
			echo "<a href=\"?".$this->__link_requisit."&amp;offset=".($this->__size+$this->__offset)."&amp;gallery=".rawurlencode($this->__gallery)."\" class=\"fg_link\">".$link."</a>";
		}else{ 
			echo "&nbsp;";
		}
	}
	
	public function first_page($img = "./a-first.png"){
		$count=1;
		while($p<$this->__list_size){
				$p=$count*$this->__size;
				$count++;
		}
		if($this->__offset!=0 && $count > 3){
			if(is_file($img)){
				list($width,$height)=getimagesize($img);
				$link="<img src=\"".$img."\" border=\"0\" alt=\"First Page\" title=\"First Page\"  height=\"".$height."\" width=\"".$width."\" />";
			}else
				$link="&lt;&lt; First Page";
				echo "<a href=\"?".$this->__link_requisit."&amp;offset=0&amp;gallery=".rawurlencode($this->__gallery)."\" class=\"fg_link\">".$link."</a>";
		}else{
			echo "&nbsp;";
		}
	}
	
	public function last_page($img = "./a-last.png"){
		$count=1;
		while($p<$this->__list_size){
				$p=$count*$this->__size;
				$count++;
		}
		$lastpage=($count-2)*$this->__size;
			
		if(($this->__size+$this->__offset)<$this->__list_size && $count > 3){
			if(is_file($img)){
				list($width,$height)=getimagesize($img);
				$link="<img src=\"".$img."\" border=\"0\" alt=\"Last Page\" title=\"Last Page\" height=\"".$height."\" width=\"".$width."\" />";
			}else
				$link="Last Page &gt;&gt;";
			echo "<a href=\"?".$this->__link_requisit."&amp;offset=".$lastpage."&amp;gallery=".rawurlencode($this->__gallery)."\" class=\"fg_link\">".$link."</a>";
		}else{ 
			echo "&nbsp;";
		}
	}
	
	public function up_gallery($img = "./a-up.png"){
		if(!empty($this->__gallery) && $this->__gallery!="/"){
			if(is_file($img)){
				list($width,$height)=getimagesize($img);
				$link="<img src=\"".$img."\" alt=\"Up\" border=\"0\" title=\"Up\" height=\"".$height."\" width=\"".$width."\" />";
			}else
				$link="^Up^";
			
			$up=substr($this->__gallery,0,strrpos($this->__gallery,"/",-2))."/";
			if($up=="/")
				$up="";
			echo "<tr><td colspan=\"".$this->__cols."\" class=\"fg_midnav\"><a href=\"?gallery=".rawurlencode($up)."\">".$link."</a></td></tr>";
		}
		return;
	}
	
	public function get_error(){
		$this->time_end = $this->microtime_float();
		$time =round( $this->time_end - $this->time_start,4);

		echo "<table class=\"fg_display_table\">\n";
		echo "<tr><td class=\"error\">".$this->error."</td></tr>";
		echo "<tr><td class=\"fg_midnav\" style=\"text-align:center;font-size:12px;\">Generated in $time seconds.<br/>Powered by <a href=\"http://flatgallery.sourceforge.net\" class=\"fg_link\">FlatGallery</a> ".$this->__VERSION." &copy; 2005-".$this->__COPY." Ben Craton</td></tr>";
	}
	
	public function get_gallery_name(){
		if(!empty($this->__gallery))
			echo ereg_replace("_"," ",ereg_replace("/"," / ",$this->__gallery));
	}
	public function get_disp_num(){
		$disp=1;
		if($this->__offset!=0 && $this->__offset>sizeof($this->__dir_index)){
				
			$disp=$this->__offset+1-$this->__bump;
			if($this->__size+$this->__offset>=$this->__list_size){
				$through= $this->__break;
			}else{
				$through= $this->__size+$this->__offset-$this->__bump;
			}
		}elseif($this->__offset!=0 && $this->__offset+$this->__size<sizeof($this->__dir_index)){
			$disp=0;
			$through=0;
		}elseif($this->__offset==0 && $this->__offset+$this->__size<sizeof($this->__dir_index)){
			$disp=0;
			$through=0;
		}elseif($this->__offset==0 && $this->__list_size>$this->__size){
			$through=$this->__size - $this->__bump;
		}elseif($this->__offset==0 && $this->__list_size<$this->__size){
			$through=$this->__list_size - $this->__bump;
		}else{
			$through=$this->__size-(sizeof($this->__dir_index)-$this->__offset);
		}

		
		if($this->__list_size-$this->__bump > 0){
			echo "Displaying images ".($disp)." - ".$through;
			echo " of ".($this->__list_size-$this->__bump);
		}
	}
	
	public function display_default(){
		$this->head();
		echo "<table class=\"fg_display_table\">\n";
		echo "<tr><td colspan=\"".$this->__cols."\" class=\"fg_title\"><a href=\"?\" class=\"fg_title\">".$this->__title."</a></td></tr>\n";

		$this->up_gallery();
		echo "<tr><td colspan=\"".$this->__cols."\" class=\"fg_midnav\">";
		$this->get_gallery_name();
		echo "</td></tr>\n";
		echo "<tr><td colspan=\"".$this->__cols."\" class=\"fg_nav\"><table class=\"fg_nav\"><tr>\n";
		echo "<td class=\"fg_leftnav\">";
		$this->first_page();
		echo "&nbsp;&nbsp;";
		$this->prev_page();
		echo "</td>\n";
		echo "<td class=\"fg_midnav\">";
		$this->get_disp_num();
		echo "</td>\n";
		echo "<td class=\"fg_rightnav\">";
		$this->next_page();
		echo "&nbsp;&nbsp;";
		$this->last_page();
		echo "</td>\n";
		echo "</tr></table></td></tr>\n";
		echo "<tr>\n";
		echo "<td colspan=\"3\">";
		$this->draw_table();
		echo "</td>";
		echo "</tr>\n";
		echo "<tr><td colspan=\"".$this->__cols."\" class=\"fg_nav\"><table class=\"fg_nav\"><tr>\n";
		echo "<td class=\"fg_leftnav\">";
		$this->first_page();
		echo "&nbsp;&nbsp;";
		$this->prev_page();
		echo "</td>\n";
		echo "<td class=\"fg_midnav\">";
		echo "</td>\n";
		echo "<td class=\"fg_rightnav\">";
		$this->next_page();
		echo "&nbsp;&nbsp;";
		$this->last_page();
		echo "</td>\n";
		echo "</tr></table></td></tr>\n";
		echo "</table>";
		$this->foot();
	}
	
	
	private function show_size($filename){
		$size=filesize($filename);
		$temp=$size;
		$i=0;
		while($temp>1024){
			$temp=$size/pow(1024,$i);
			$i++;
		}
		$temp=round($temp,0);
		switch($i){
			case 0: $size=$temp." b"; break;
			case 1: $size=$temp." B"; break;
			case 2: $size=$temp." KB"; break;
			case 3: $size=$temp." MB"; break;
		}
		return $size;
	}
	
	public function link_stylesheet(){
			echo "<link href=\"".$this->__stylesheet."\" rel=\"stylesheet\" type=\"text/css\" />\n";
	}
	
	public function head(){
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
		echo "<head>\n";
		echo "<title>".$this->__title."</title>\n";
		$this->link_stylesheet();
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
		echo "</head>\n";
		echo "<body>\n";
	}
	public function foot(){
		echo "</body>\n";
		echo "</html>\n";
	}
	
}


?>
