# PSFS
PHP Simple File Server

This is a really simple solution to build a Central File Server for your site.

#Setup a place for your source code and Uploaded Files

First, you need to setup a place to store the code and uploaded files.

mkdir ~/htdocs
cd htdocs
mkdir files
mkdir images

#Install Apache or Nginx as your web server

Open theweb server configuration file, edit or add location block, point to the code path.

        location / {
            root   pathto/htdocs;
            index  index.php index.html index.htm;
        }

# Install PHP

#Test File Server
1. Open http://localhost/index.html, if you can see the form, the file server is up.
2. Select a file and click submit, if you see a json string contains code and message, the file server should be running properly.

Java Code block to upload a file:
  String fileServerInnerUrl = "http://192.168.10.3"; //LAN
  String fileServerPublicUrl="http://xxx.xxx.com"; //WAN
	CloseableHttpClient httpClient = HttpClients.createDefault();
	HttpPost httpPost = new HttpPost(fileServerInnerUrl + "file-upload.php");
	MultipartEntityBuilder meb = MultipartEntityBuilder.create();
	meb.addPart("file", new FileBody(file));
	HttpEntity reqEntity = meb.build(); 
	httpPost.setEntity(reqEntity);
	String strResult = null;
	try {
		CloseableHttpResponse response = httpClient.execute(httpPost);
		if (response.getStatusLine().getStatusCode() == 200) {
			strResult = EntityUtils.toString(response.getEntity(), "UTF-8");
			JSONObject jsonObject = JSONObject.parseObject(strResult);
			//If the code is "0", the file is uploaded, string message is the path.
			String code = jsonObject.getString("code");
			//If the code is not "0", file uploaded should be failed, then the message is error message.
			String message = jsonObject.getString("message");
			if("0".equals(code))
			{
			    //fileURL is the url of uploaded file, can be used directly in your web page.
			     String fileURL = fileServerPublicUrl + message;
			}
		}
	} catch (Exception e) {
		e.printStackTrace();
	}


