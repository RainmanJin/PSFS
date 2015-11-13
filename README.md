# PSFS
PHP Simple File Server

This is a really simple solution to build a Central File Server for your site.

极简版的PHP文件服务器，可以应用于对文件服务性能和可用性要求不高的网站。
只有两个PHP文件，可以实现接收和保存POST过来的文件，并返回文件URL的功能。后续会继续进行扩展。

#Setup a place for your source code and Uploaded Files 创建PHP代码和文件保存目录

First, you need to setup a place to store the code and uploaded files.

mkdir ~/htdocs
cd htdocs
mkdir files
mkdir images

#Install Apache or Nginx as your web server 安装Nginx或Apache作为Web服务器

Open theweb server configuration file, edit or add location block, point to the code path.

        location / {
            root   pathto/htdocs;
            index  index.php index.html index.htm;
        }

# Install PHP 安装PHP

#Test File Server 测试文件服务器
1. Open http://localhost/index.html, if you can see the form, the file server is up. 启动web服务器后，输入localhost/index.html，如果能够看到文件上传的Form表示服务已经启动。
2. Select a file and click submit, if you see a json string contains code and message, the file server should be running properly. 选择一个文件并点击提交，如果能够看到返回的包含返回码和消息的json字符串，文件服务器应该就正常运行了。

Java Code block to upload a file:
以下是实现文件上传的Java示例代码。 上传时以局域网的方式上传，返回则是可以直接在网站上引用的文件或图片的URL。
<pre><code>
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
</pre></code>
