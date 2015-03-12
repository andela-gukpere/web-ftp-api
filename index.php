<?php
		include('ftp_class.php');
		$ftpObj = new FTPClient();
		$ftph = $_GET["host"];$ftpu=$_GET["u"];$ftpp=$_GET["p"];

		$dir=$_GET["dir"];
		$file=$_GET["file"];
		$do = $_GET["do"];
		if(isset($ftph,$ftpu,$ftpp))
		{
			if ($ftpObj->connect($ftph, $ftpu, $ftpp,true)) 
			{
	
				$dir = isset($dir)?$dir:"/";		
				$do= isset($do)?$do:0;
				$do=intval($do);
				switch($do)
				{
					case 11://Open File of Directory
						$brw = $_GET["res_res"];
						if(!isset($_GET["json"]))
						{
							exit("(".retjson('{"0":"If you are seeing this message, it means your app version is obselete and a newer version of <a href=\'#\'onclick=\'openUrl(\"http://appworld.blackberry.com/webstore/content/22458014/?lang=en\")\'><b>Repose Editor Pro</b></a> is available, please get the latest version of your <b>app</b>","success":"0"}','1','',400).")");
						}
						if(isset($brw)&&$brw==101)sleep(3);
						$gdr = $ftpObj->getDirListing($dir);												
						if(!$gdr)exit(retjson('{'.'"1":"'.$dir.'","success":"0","0":"Invalid directory change"'.'}',1,'',400));
						$dr=json_encode($gdr[1]);
						$dir=$gdr[0]; 
						exit(retjson('{'.'"1":"'.$dir.'","0":'.$dr.'}',$do));
					break;
					case 12://Make Directory
						$newdir=$_GET["newdir"];
						$mkd = @$ftpObj->makeDir($dir.$newdir);
						$res = "Error creaking directory <B>$newdir</b> in <b>$dir</b>";
						$res2=$mkd?1:0;
						$dir=($dir.$newdir);
						if($mkd)$res = "Created Directory: $dir";
						exit(retjson('{"success":"'.$res2.'","0":"Created Directory: '.$dir.'"}',$do));
					break;
					case 13: //download file
							$fileDir= "./ftp/".uniqid("ftp-")."-temp/";
							$fileTo = $fileDir.$file;
							mkdir($fileDir);
							$fl = $ftpObj->downloadFile($dir.$file, $fileTo);
							if($fl)
							{
								fclose($fl);
								$t=end(explode(".",$file));
								header("Content-Type: application/octet-stream");//$t
								header("Content-Length: ".filesize($fileTo));
								header("Content-Disposition: attachment;filename=$file");
								header('Content-Transfer-Encoding: binary');
								readfile($fileTo);
							}
							else
							{
								@unlink($fileTo);
								@rmdir($fileDir);
								exit(retjson('{"success":"0","0":"File ['.$file.'] not found on server"}',$do,"",404));
							}
							@unlink($fileTo);
							@rmdir($fileDir);
							exit();
					break;	
					case 14: //upload file
						$fileFrom = $_FILES["upl"]["tmp_name"];	
						$ftpObj->changeDir($dir);
						//$file=$_REQUEST["file"];
						$ff = isset($file)?$file:strval($_FILES["upl"]["name"]);
						if(is_uploaded_file($fileFrom) && !stristr($ff,"/") && strlen($ff)>0)
						{
							$ff= strval($ff);
							$fileTo=$ff;
							$upd=$ftpObj->uploadFile($fileFrom, $fileTo);
						}
						$st=$upd?1:0;
						$str=$upd?"$ff uploaded to $dir":"Error uploading $ff to $dir ";
					//	if(isset($_GET["dojs"]))
					//	exit("Done");//exit("<script>alert('$str')/script>");
					//	else
						exit(retjson('{"success":"'.$st.'",'.'"0":"'.$str.'"'.'}',$do));
					break;
					case 15: //change directory
							
							$ftpObj->changeDir($dir);
				
							// *** Get folder contents
							$contentsArray = $ftpObj->getDirListing();
				
				
							echo '<pre>';
							print_r($contentsArray);
							echo '</pre>';
				
							## --------------------------------------------------------
					break;
					case 16: //Rename File
						$newn=$_GET["newname"];
					    $ftpObj->changeDir($dir);
						$res=@$ftpObj->renm($file,$newn);	
						$ret = $res?"Success renamed $file to $newn":"Error renaming $file";
						$st = $res?1:0;
						exit(retjson('{"0":"'.$ret.'","success":"'.$st.'"}',$do));

					break;
					case 17: //delete file
					    $ftpObj->changeDir($dir);
						$res=@$ftpObj->delF($file);
						$ret = $res?"Successfully deleted $file":"Error deleting $file";
						$st = $res?1:0;
						exit(retjson('{"0":"'.$ret.'","success":"'.$st.'"}',$do));
					break;
					case 21:
						$ftpObj->go_up($dir);			
					break;
					default:
					exit(retjson('{"0":"Host:'.$ftph.' not found '.$do.'","success":"0"}',1,'',400));
					break;
				}				
			} 
			else
			exit(retjson('{"0":"Login Error, confirm your login credentials, and HOST <em>addr</em>","success":"0"}','1','',400));
		}
		else
		{
			//exit(retjson('{"0":"No input params receives"}','1'));	
		}
		//exit("No longer avaliable for public usage");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta content='width=device-width,initial-scale=0.90,minimum-scale=0.90,maximum-scale=0.90,user-scalable=no' name='viewport' />
<style>
body{font-family:"Palatino Linotype", "Book Antiqua", Palatino, serif;color:222;margin:0;padding:0}
a{color:#09F; text-decoration:none;}a:hover{color:#01f;}a:visited{color:#888;}h1,h2,h3,h4{font-variant:small-caps;padding:1px;margin:2px;}pre,.pre{font-weight:800;text-wrap:suppress !important;word-wrap:break-word !important;background-color:#efefef;color:#444; display:inline-block;font-variant:normal;}div.itd{padding:10px 5px 10px 5px;border-bottom:1px dashed #888;margin:0 0 20px 0;}
em{color:#f04304;}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FTP api - Documentation</title>
</head>
<body>
<div style="padding:0 10px 0 10px;">
<h2>Web FTP api <b style="font-size:15px; color:#999;">version 0.9.1.1</b> - Documentation 
  <h4>(web based ftp api via <a>http</a>)</h4></h2><hr/>
<div>
<h3>How to Use</h3>
<code><p>This is a web based FTP api</p>
<p>It uses only <b>GET</b> parameters (POST only for file to be uploaded)</p>
<p>The api will be explained briefly below</p>
<p>URL for api (You are HERE):<a href="http://api.godson.com.ng/ftp">http://api.godson.com.ng/ftp</a></p>
<p>You get will receive this information if you don't input the required get parameters for the api</p>
</code>
</div>
<div>
<h3 id="top">api topics</h3>
<h4><a href="#login">1. Login and File/Directory Listing</a></h4>
<h4><a href="#cdr">2. Create Directory</a></h4>
<h4><a href="#dlf">3. Download File</a></h4>
<h4><a href="#uplf">4. Upload File</a></h4>
<h4><a href="#renm">5. Rename File</a></h4>
<h4><a href="#delf">6. Delete File</a></h4>
<h4><a href="#faqs">F.a.Q.s</a></h4>
</div>

<div class="itd">
<h4 id="login">
1. Login and File/Directory listing <span class="pre">[do=11]</span>
</h4>
<code>
To login simply add the query string to the api url<p>
<pre>[api_url]?host=<em>[ftp host]</em>&amp;u=<em>[ftp username]</em>&amp;dir=<em>[Path/Directory]</em>&amp;p=<em>[ftp password]</em>&amp;do=11&amp;json=2</pre></p>
<p>For Example <span class="pre">http://api.godson.com.ng/ftp/?host=<em>godson.com.ng</em>&amp;u=<em>godson</em>&amp;p=<em>ukpere</em>&amp;do=11&amp;json=2&amp;dir=<em>/</em></span></p>
<p>This returns <span class="pre">{"obj":{1:"<em>[your current path]</em>","0":{[[<em>"file/folder-1</em>","<em>size(bytes)</em>"],["<em>file/folder-2</em>","<em>size</em>"]].....["<em>file/folder(n)</em>","<em>size</em>"]}},"do":[&quot;<em>11</em>&quot;/*the <B>do</B> you put in value*/]}</span><br/>if successfull</p>
<p>OR</p>
<p>This on login error <span class="pre">{&quot;status&quot;:&quot;400&quot;,"obj":{"0":"Login Error","success":"0"},"do":&quot;1&quot;}</span><br/>
<B>Once there is an authentication error, you get this json result.</B>
</p>
</code>
<div style="height:15px;"><a style="float:right;" href="#top">Back to Top</a></div>
</div>

<div class="itd">
<h4 id="cdr">
2. Create Directory <span class="pre">[do=12]</span>
</h4>
<code>
To create a directory <p><pre>[api_url]?host=<em>[ftp host]</em>&amp;u=<em>[ftp username]</em>&amp;dir=<em>[Path/Directory]</em>&amp;p=<em>[ftp password]</em>&amp;do=12&amp;newdir=<em>[new directory name]</em></pre></p>
<p>For Example <span class="pre">http://api.godson.com.ng/ftp/?host=<em>godson.com.ng</em>&amp;u=<em>godson</em>&amp;p=<em>ukpere</em>&amp;do=11&amp;dir=<em>/public_html/</em>&amp;newdir=<em>books</em></span></p>
<p>This returns <span class="pre">{"obj":{&quot;success&quot;:<em>1</em>;&quot;1&quot;:"[current path]","0":&quot;[some success message]&quot;},"do":&quot;12&quot;}</span><br/>
  if successfull</p>
<p>OR</p>
<p>This on error <span class="pre">{"obj":{&quot;success&quot;:&quot;<em>0</em>&quot;,[.....]},"do":&quot;12&quot;};</span><br/>
<B>NOTE. There must be a traling and leading <em>/</em> in the <span class="pre">dir</span> value hence the <span class="pre">/public_html/</span> while the new directory name should just be the name without any prefix/suffix.</B>
</p>
</code>
<div style="height:15px;"><a style="float:right;" href="#top">Back to Top</a></div>
</div>

<div class="itd">
<h4 id="dlf">
3. Download File <span class="pre">[do=13]</span>
</h4>
<code>
To download a file<p><pre>[api_url]?host=<em>[ftp host]</em>&amp;u=<em>[ftp username]</em>&amp;dir=<em>[Path/Directory]</em>&amp;p=<em>[ftp password]</em>&amp;do=13&amp;file=<em>[Filename]</em></pre></p>
<p>For Example <span class="pre">http://api.godson.com.ng/ftp/?host=<em>godson.com.ng</em>&amp;u=<em>godson</em>&amp;p=<em>ukpere</em>&amp;do=13&amp;dir=<em>/</em>&amp;file=<em>icon.png</em></span></p>
<p>This returns the file for download if the file is found</p>
<p>OR</p>
<p>This on File not found <span class="pre">{&quot;status&quot;:&quot;404&quot;,"obj":{"success":"0","0":&quot;File [<em>File name</em>] not found on server&quot;},"do":&quot;13&quot;}</span>
</p>
<b>NOTE, the <span class="pre">http://api.godson.com.ng/ftp/[temp_path]/[filename]</span> for the http delivered file from the FTP server is ephemeral, and will be deleted on transfer completion.</b>
</code>
<div style="height:15px;"><a style="float:right;" href="#top">Back to Top</a></div>
</div>

<div class="itd">
<h4 id="uplf">
4. Upload File <span class="pre">[do=14]</span>
</h4>
<code>
To upload a file create a form and set the action to<p><pre>[api_url]?host=<em>[ftp host]</em>&amp;u=<em>[ftp username]</em>&amp;dir=<em>[Path/Directory]</em>&amp;p=<em>[ftp password]</em>&amp;do=14&amp;file=<em>[file name]</em></pre> and the name of the input[type=file] should be <B>upl</B></p>
<p>For Example</p>
<p>The form must contain the following</p>
<pre>&lt;form action='<pre>http://api.godson.com.ng/ftp/?host=<em>godson.com.ng</em>&amp;u=<em>godson</em>&amp;dir=<em>/public_html/</em>&amp;p=<em>ukpere</em>&amp;do=14</pre>' enctype='multipart/form-data' method='post'&gt;</pre>
<?php for($i=0;$i<4;$i++)echo "<p style='margin:0 0 0 40px;padding:0;'>.</p>"; ?>
<pre>&lt;input type='file' name='upl' /&gt;</pre>

<p>This returns <span class="pre">{"status":"200","obj":{"success":"1","0":&quot;<em>[File Name]</em> uploaded to <em>[Full path]</em>&quot;},"do":&quot;14&quot;}</span></p>
<p>OR</p>
<p>This on error <span class="pre">{"obj":{"success":"0",[.....]},"do":&quot;14&quot;}</span>
</p>
<p>NOTE.. if filename is not provided, the name of the uploaded file will be used, but it's safer to provide the name to avoid errors. You should send the filename input<em>[name=file]</em> via <B>GET</B></p>
</code>
<div style="height:15px;"><a style="float:right;" href="#top">Back to Top</a></div>
</div>

<div class="itd">
<h4 id="renm">
5. Rename File / Directory <span class="pre">[do=16]</span>
</h4>
<code>
To rename a file or directory<p><pre>[api_url]?host=<em>[ftp host]</em>&amp;u=<em>[ftp username]</em>&amp;dir=<em>[Path/Directory]</em>&amp;p=<em>[ftp password]</em>&amp;file=<em>[current file/directory name]</em>&amp;newname=<em>[new file/directory name]</em>&amp;do=16</pre></p>

<p>For Example</p>
<pre>http://api.godson.com.ng/ftp/?host=<em>godson.com.ng</em>&amp;u=<em>godson</em>&amp;p=<em>ukpere</em>&amp;do=16&amp;dir=<em>/public_html/</em>&amp;file=<Em>icon.png</Em>&amp;newname=<em>favicon.png</em></pre>
<p>This returns <span class="pre">{"status":"200","obj":{"0":&quot;Successfully renamed <em>[old file name]</em> to <em>[new file name]</em>&quot;,&quot;success&quot;:&quot;1&quot;},"do":&quot;16&quot;}</span></p>
<p>OR</p>
<p>This on error <span class="pre">{"status":"200","obj":{"0":&quot;Error renaming <em>[old file/directory name]</em>&quot;,&quot;success&quot;:&quot;0&quot;},"do":&quot;16&quot;}</span>
</p>
</code>
<div style="height:15px;"><a style="float:right;" href="#top">Back to Top</a></div>
</div>

<div class="itd">
<h4 id="delf">
6. Delete File / Directory <span class="pre">[do=17]</span>
</h4>
<code>
To delete a file or directory<p><pre>[api_url]?host=<em>[ftp host]</em>&amp;u=<em>[ftp username]</em>&amp;dir=<em>[Path/Directory]</em>&amp;p=<em>[ftp password]</em>&amp;file=<em>[current file/directory name]</em>&amp;do=17</pre></p>

<p>For Example</p>
<pre>http://api.godson.com.ng/ftp/?host=<em>godson.com.ng</em>&amp;u=<em>godson</em>&amp;p=<em>ukpere</em>&amp;do=17&amp;dir=<em>/public_html/</em>&amp;file=<Em>icon.png</Em>&amp;newname=<em>favicon.png</em></pre>
<p>This returns <span class="pre">{"status":"200","obj":{"0":"Successfully deleted <em>[file/directory name]</em>","success":"1"},"do":&quot;17&quot;}</span></p>
<p>OR</p>
<p>This on error <span class="pre">{"status":"200","obj":{"0":&quot;Error deleting <em>[file/directory name]</em>&quot;,&quot;success&quot;:&quot;0&quot;},"do":&quot;17&quot;}</span>
</p>
</code>
<div style="height:15px;"><a style="float:right;" href="#top">Back to Top</a></div>
</div>



<div>
<h2 id="faqs">f.a.qs</h2>
<ul>
	<li>Items in color <em>#f04504</em> are variables and you can use your customized values to replace them, all others are compulsory.</li>
    <li><em>[ftp host]</em> can be a domain name or IP address (IPv4) of your FTP server.</li>
    <li><em>[Path/Directory]</em> is used to represent the current folder you wish to carry out an operation <b>IN</b> not <b>ON</b>	except when you wish to retrieve the list of files or directories in <a href="#login"><span class="pre">[do=11]</span></a>.</li>
    <li>When working on a file or directory [renaming or deleting] <span class="pre">file</span>, <span class="pre">newname</span> should not contain any form of slashes <span class="pre">\ or /</span> only <em>[Path\Directory]</em> should begin and end with a <span class="pre">/</span>.</li>
    <li><span class="pre">do</span> [<em>typeof(<span class="pre">int</span>|<span class="pre">number</span>)</em>] refers the <b>action</b> of the api, use the <span class="pre">do</span> value that corressponds to the ftp action you wish to execute.</li>
    <li>File size of <span class="pre">-1 </span>bytes denotes a folder, hence an empty file will have size of <span class="pre">0</span> bytes.</li>
</ul>
<div style="height:15px;"><a style="float:right;" href="#top">Back to Top</a></div>
</div>
<Div style="margin-top:80px;">
<div style="width:400px; margin:0px auto; padding:20px; font-variant:small-caps; border-top:1px solid #444; text-align:center">
&copy;2012 <a href="//godson.com.ng" target="_blank">Godson Ukpere</a><Br/><a href="//api.godson.com.ng/ftp">FTP api</a></div>
</Div>
</div>
</body>
</html> 
