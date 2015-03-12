### Web FTP api **version 0.9.1.1** - Documentation

##### (web based ftp api via [http]())

* * *

#### How to Use

This is a web based FTP api

It uses only **GET** parameters (POST only for file to be uploaded)

The api will be explained briefly below

URL for api (You are HERE):[http://api.godson.com.ng/ftp](http://api.godson.com.ng/ftp)

You get will receive this information if you don't input the required get parameters for the api

#### API topics

##### [1. Login and File/Directory listing](#1-login-and-filedirectory-listingdo11)

##### [2. Create Directory](#2-create-directorydo12)

##### [3. Download File](#3-download-filedo13)

##### [4. Upload File](#4-upload-filedo14)

##### [5. Rename File](#5-rename-file--directorydo16)

##### [6. Delete File](#-6-delete-file--directorydo17)

##### [FAQs](#faqs-1)

## 1. Login and File/Directory listing [do=11]

To login simply add the query string to the api url

[api_url]?host=[ftp host]&u=[ftp username]&dir=[Path/Directory]&p=[ftp password]&do=11&json=2

For Example http://api.godson.com.ng/ftp/?host=godson.com.ng&u=godson&p=ukpere&do=11&json=2&dir=/

This returns `{"obj":{1:"[your current path]","0":{[["file/folder-1","size(bytes)"],["file/folder-2","size"]].....["file/folder(n)","size"]}},"do":["11"/*the **do** you put in value*/]}`
if successfull

OR

This on login error `{"status":"400","obj":{"0":"Login Error","success":"0"},"do":"1"}`
**Once there is an authentication error, you get this json result.**

[Back to Top](#top)

## 2. Create Directory [do=12]

To create a directory

`[api_url]?host=[ftp host]&u=[ftp username]&dir=[Path/Directory]&p=[ftp password]&do=12&newdir=[new directory name]`

For Example `http://api.godson.com.ng/ftp/?host=godson.com.ng&u=godson&p=ukpere&do=11&dir=/public_html/&newdir=books`

This returns `{"obj":{"success":1;"1":"[current path]","0":"[some success message]"},"do":"12"}`
if successfull

OR

This on error `{"obj":{"success":"0",[.....]},"do":"12"};`
**NOTE. There must be a traling and leading _/_ in the dir value hence the /public_html/ while the new directory name should just be the name without any prefix/suffix.**

[Back to Top](#top)

## 3. Download File [do=13]

To download a file

`[api_url]?host=[ftp host]&u=[ftp username]&dir=[Path/Directory]&p=[ftp password]&do=13&file=[Filename]`

For Example `http://api.godson.com.ng/ftp/?host=godson.com.ng&u=godson&p=ukpere&do=13&dir=/&file=icon.png`

This returns the file for download if the file is found

OR

This on File not found `{"status":"404","obj":{"success":"0","0":"File [_File name_] not found on server"},"do":"13"}`

**NOTE, the http://api.godson.com.ng/ftp/[temp_path]/[filename] for the http delivered file from the FTP server is ephemeral, and will be deleted on transfer completion.**

[Back to Top](#top)

## 4. Upload File [do=14]

To upload a file create a form and set the action to

`[api_url]?host=[ftp host]&u=[ftp username]&dir=[Path/Directory]&p=[ftp password]&do=14&file=[Filename]` and the name of the input[type=file] should be **upl**

For Example

The form must contain the following

```
<form action='http://api.godson.com.ng/ftp/?host=godson.com.ng&amp;u=godson&amp;dir=/public_html/&amp;p=ukpere&amp;do=14' enctype='multipart/form-data' method='post'>

.

.

.

.

<input type='file' name='upl' />
</form>
```

This returns `{"status":"200","obj":{"success":"1","0":"[File Name] uploaded to [Full path]"},"do":"14"}`

OR

This on error `{"obj":{"success":"0",[.....]},"do":"14"}`

NOTE.. if filename is not provided, the name of the uploaded file will be used, but it's safer to provide the name to avoid errors. You should send the filename input_[name=file]_ via **GET**

[Back to Top](#top)

##### 5. Rename File / Directory [do=16]

To rename a file or directory

`[api_url]?host=[ftp host]&u=[ftp username]&dir=[Path/Directory]&p=[ftp password]&file=[current file/directory name]&newname=[new file/directory name]&do=16`

For Example

`http://api.godson.com.ng/ftp/?host=godson.com.ng&u=godson&p=ukpere&do=16&dir=/publichtml/&file=icon.png&newname=favicon.png`

This returns {"status":"200","obj":{"0":"Successfully renamed [old file name] to [new file name]","success":"1"},"do":"16"}

OR

This on error `{"status":"200","obj":{"0":"Error renaming [old file/directory name]","success":"0"},"do":"16"}`

[Back to Top](#top)

## 6. Delete File / Directory [do=17]

To delete a file or directory

`[api_url]?host=[ftp host]&u=[ftp username]&dir=[Path/Directory]&p=[ftp password]&file=[current file/directory name]&do=17`

For Example

http://api.godson.com.ng/ftp/?host=godson.com.ng&u=godson&p=ukpere&do=17&dir=/public_html/&file=icon.png&newname=_favicon.png_

This returns `{"status":"200","obj":{"0":"Successfully deleted [file/directory name]","success":"1"},"do":"17"}`

OR

This on error {"status":"200","obj":{"0":"Error deleting [file/directory name]","success":"0"},"do":"17"}

[Back to Top](#top)

## FAQs

- Items in color _#f04504_ are variables and you can use your customized values to replace them, all others are compulsory.
- [ftp host] can be a domain name or IP address (IPv4) of your FTP server.
- [Path/Directory] is used to represent the current folder you wish to carry out an operation **IN** not **ON**	except when you wish to retrieve the list of files or directories in [[do=11]](#login).
- When working on a file or directory [renaming or deleting] file, newname should not contain any form of slashes \ or / only _[Path\Directory]_ should begin and end with a /.
- do [_typeof(int|number)_] refers the **action** of the

api, use the do value that

corressponds to the

ftp action you wish to execute.
- File size of -1bytes denotes a folder, hence an empty file will have size of 0 bytes.

[Back to Top](#top)

©2012 [Godson Ukpere](http://godson.com.ng/)  [FTP api](http://api.godson.com.ng/ftp)
