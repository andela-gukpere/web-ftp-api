### Web FTP api **version 0.9.1.1** - Documentation

##### (web based ftp api via [http]())

* * *

#### How to Use

This is a web based FTP api

It uses only **GET** parameters (POST only for file to be uploaded)

The api will be explained briefly below

URL for api (You are HERE):[http://api.godson.com.ng/ftp](http://api.godson.com.ng/ftp)

You get will receive this information if you don't input the required get parameters for the api

#### api topics

##### [1. Login and File/Directory Listing](http://api.godson.com.ng/ftp/#login)

##### [2. Create Directory](http://api.godson.com.ng/ftp/#cdr)

##### [3. Download File](http://api.godson.com.ng/ftp/#dlf)

##### [4. Upload File](http://api.godson.com.ng/ftp/#uplf)

##### [5. Rename File](http://api.godson.com.ng/ftp/#renm)

##### [6. Delete File](http://api.godson.com.ng/ftp/#delf)

##### [F.a.Q.s](http://api.godson.com.ng/ftp/#faqs)

##### 1. Login and File/Directory listing [do=11]

To login simply add the query string to the api url

[api_url]?host=_[ftp host]_&u=_[ftp username]_&dir=_[Path/Directory]_&p=_[ftp password]_&do=11&json=2

For Example http://api.godson.com.ng/ftp/?host=_godson.com.ng_&u=_godson_&p=_ukpere_&do=11&json=2&dir=_/_

This returns `{"obj":{1:"_[your current path]_","0":{[[_"file/folder-1_","_size(bytes)_"],["_file/folder-2_","_size_"]].....["_file/folder(n)_","_size_"]}},"do":["_11_"/*the **do** you put in value*/]}`  
if successfull

OR

This on login error `{"status":"400","obj":{"0":"Login Error","success":"0"},"do":"1"}`
**Once there is an authentication error, you get this json result.**

[Back to Top](http://api.godson.com.ng/ftp/#top)

##### 2. Create Directory [do=12]

To create a directory

`[api_url]?host=_[ftp host]_&u=_[ftp username]_&dir=_[Path/Directory]_&p=_[ftp password]_&do=12&newdir=_[new directory name]_`

For Example `http://api.godson.com.ng/ftp/?host=_godson.com.ng_&u=_godson_&p=_ukpere_&do=11&dir=_/public_html/_&newdir=_books_`

This returns `{"obj":{"success":_1_;"1":"[current path]","0":"[some success message]"},"do":"12"}`  
if successfull

OR

This on error `{"obj":{"success":"_0_",[.....]},"do":"12"};`  
**NOTE. There must be a traling and leading _/_ in the dir value hence the /public_html/ while the new directory name should just be the name without any prefix/suffix.**

[Back to Top](http://api.godson.com.ng/ftp/#top)

##### 3. Download File [do=13]

To download a file

`[api_url]?host=_[ftp host]_&u=_[ftp username]_&dir=_[Path/Directory]_&p=_[ftp password]_&do=13&file=_[Filename]_`

For Example `http://api.godson.com.ng/ftp/?host=_godson.com.ng_&u=_godson_&p=_ukpere_&do=13&dir=_/_&file=_icon.png_`

This returns the file for download if the file is found

OR

This on File not found `{"status":"404","obj":{"success":"0","0":"File [_File name_] not found on server"},"do":"13"}`

**NOTE, the http://api.godson.com.ng/ftp/[temp_path]/[filename] for the http delivered file from the FTP server is ephemeral, and will be deleted on transfer completion.**

[Back to Top](http://api.godson.com.ng/ftp/#top)

##### 4. Upload File [do=14]

To upload a file create a form and set the action to

`[api_url]?host=_[ftp host]_&u=_[ftp username]_&dir=_[Path/Directory]_&p=_[ftp password]_&do=14&file=_[file name]_` and the name of the input[type=file] should be **upl**

For Example

The form must contain the following

```
<form action='http://api.godson.com.ng/ftp/?host=_godson.com.ng_&u=_godson_&dir=_/public_html/_&p=_ukpere_&do=14' enctype='multipart/form-data' method='post'>

.

.

.

.

<input type='file' name='upl' />
```

This returns `{"status":"200","obj":{"success":"1","0":"_[File Name]_ uploaded to _[Full path]_"},"do":"14"}`

OR

This on error `{"obj":{"success":"0",[.....]},"do":"14"}`

NOTE.. if filename is not provided, the name of the uploaded file will be used, but it's safer to provide the name to avoid errors. You should send the filename input_[name=file]_ via **GET**

[Back to Top](http://api.godson.com.ng/ftp/#top)

##### 5. Rename File / Directory [do=16]

To rename a file or directory

`[api_url]?host=_[ftp host]_&u=_[ftp username]_&dir=_[Path/Directory]_&p=_[ftp password]_&file=_[current file/directory name]_&newname=_[new file/directory name]_&do=16`

For Example

`http://api.godson.com.ng/ftp/?host=godson.com.ng&u=godson&p=ukpere&do=16&dir=/publichtml/&file=icon.png&newname=favicon.png`

This returns {"status":"200","obj":{"0":"Successfully renamed _[old file name]_ to _[new file name]_","success":"1"},"do":"16"}

OR

This on error `{"status":"200","obj":{"0":"Error renaming _[old file/directory name]_","success":"0"},"do":"16"}`

[Back to Top](http://api.godson.com.ng/ftp/#top)

##### 6. Delete File / Directory [do=17]

To delete a file or directory

`[api_url]?host=_[ftp host]_&u=_[ftp username]_&dir=_[Path/Directory]_&p=_[ftp password]_&file=_[current file/directory name]_&do=17`

For Example

http://api.godson.com.ng/ftp/?host=_godson.com.ng_&u=_godson_&p=_ukpere_&do=17&dir=_/public_html/_&file=_icon.png_&newname=_favicon.png_

This returns {"status":"200","obj":{"0":"Successfully deleted _[file/directory name]_","success":"1"},"do":"17"}

OR

This on error {"status":"200","obj":{"0":"Error deleting _[file/directory name]_","success":"0"},"do":"17"}

[Back to Top](http://api.godson.com.ng/ftp/#top)

### f.a.qs

- Items in color _#f04504_ are variables and you can use your customized values to replace them, all others are compulsory.
- _[ftp host]_ can be a domain name or IP address (IPv4) of your FTP server.
- _[Path/Directory]_ is used to represent the current folder you wish to carry out an operation **IN** not **ON**	except when you wish to retrieve the list of files or directories in [[do=11]](http://api.godson.com.ng/ftp/#login).
- When working on a file or directory [renaming or deleting] file, newname should not contain any form of slashes \ or / only _[Path\Directory]_ should begin and end with a /.
- do [_typeof(int|number)_] refers the **action** of the 

api, use the do value that 

corressponds to the 

ftp action you wish to execute.
- File size of -1bytes denotes a folder, hence an empty file will have size of 0 bytes.

[Back to Top](http://api.godson.com.ng/ftp/#top)

©2012 [Godson Ukpere](http://godson.com.ng/)  [FTP api](http://api.godson.com.ng/ftp)
