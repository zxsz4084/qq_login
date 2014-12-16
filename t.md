第一步
https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=101177686&redirect_uri=http%3A%2F%2Fqq.pengduncun.com%2Fcallback.php&state=202f00d1b44de181ec7f82cc5ff4826d

302跳转

如果qq是登录状态 只需要确定登录 which=ConfirmPage
http://openapi.qzone.qq.com/oauth/show?which=ConfirmPage&display=pc&response_type=code&client_id=101177686&redirect_uri=http%3A%2F%2Fqq.pengduncun.com%2Fcallback.php&state=202f00d1b44de181ec7f82cc5ff4826d

需要登录 which=Login
http://openapi.qzone.qq.com/oauth/show?which=Login&display=pc&response_type=code&client_id=101177686&redirect_uri=http%3A%2F%2Fqq.pengduncun.com%2Fcallback.php&state=202f00d1b44de181ec7f82cc5ff4826d

登录动作post请求 https://graph.qq.com/oauth2.0/authorize
auth_time	1418300324108
client_id	101177686
g_tk	594089861
openapi	#
redirect_uri	http://qq.pengduncun.com/callback.php
response_type	code
scope	
src	1
state	a4b34c2bcb475f28b64805671ed8f461
ui	8C58E69C-30C6-429A-8B19-F6BCEC982444
update_auth	

302跳转到
http://qq.pengduncun.com/callback.php?code=21508539322A4CA2199E13828265E673&state=a4b34c2bcb475f28b64805671ed8f461

第二步 返回code
callback.php 发出请求获取access_token

https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=101177686&redirect_uri=http%3A%2F%2Fqq.pengduncun.com%2Fcallback.php&client_secret=fd269042177c4915a64daf7d2bdb2bc0&code=F7617D9C9D29F835B9BE94BDE6F729B8

获取到 access_token=YOUR_ACCESS_TOKEN&expires_in=3600

第三步 获取到openid
https://graph.qq.com/oauth2.0/me?access_token=YOUR_ACCESS_TOKEN


http://qzonestyle.gtimg.cn/qzone/openapi/redirect-1.0.1.html
http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js
http://qzonestyle.gtimg.cn/qzone/openapi/qc-1.0.1.js

