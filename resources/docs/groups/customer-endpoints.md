# Customer Endpoints

Customer Apis

## Auth: Country List




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/country_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/country_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Country List Found",
    "data": [
        {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/flags\/india.png"
        }
    ]
}
```
<div id="execution-results-GETapi-customer-country_list" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-country_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-country_list"></code></pre>
</div>
<div id="execution-error-GETapi-customer-country_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-country_list"></code></pre>
</div>
<form id="form-GETapi-customer-country_list" data-method="GET" data-path="api/customer/country_list" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-country_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-country_list" onclick="tryItOut('GETapi-customer-country_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-country_list" onclick="cancelTryOut('GETapi-customer-country_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-country_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/country_list</code></b>
</p>
</form>


## Auth: Signup




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/signup" \
    -H "Content-Type: multipart/form-data" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -F "first_name=John" \
    -F "company_name=John" \
    -F "email=John@gmail.com" \
    -F "mobile_number=1234567890" \
    -F "password=John@123" \
    -F "referral_code=SSGC1234" \
    -F "device_type=iphone" \
    -F "device_token=abcd1234" \
    -F "profile_image=@C:\Users\ronak.soni\AppData\Local\Temp\php78BC.tmp"     -F "company_images[]=@C:\Users\ronak.soni\AppData\Local\Temp\php78CC.tmp"     -F "company_documents[]=@C:\Users\ronak.soni\AppData\Local\Temp\php78CD.tmp" 
```

```javascript
const url = new URL(
    "http://localhost/api/customer/signup"
);

let headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
    "Accept-Language": "en",
};

const body = new FormData();
body.append('first_name', 'John');
body.append('company_name', 'John');
body.append('email', 'John@gmail.com');
body.append('mobile_number', '1234567890');
body.append('password', 'John@123');
body.append('referral_code', 'SSGC1234');
body.append('device_type', 'iphone');
body.append('device_token', 'abcd1234');
body.append('profile_image', document.querySelector('input[name="profile_image"]').files[0]);
body.append('company_images[]', document.querySelector('input[name="company_images[]"]').files[0]);
body.append('company_documents[]', document.querySelector('input[name="company_documents[]"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "OTP sent to your email test123@mailinator.com",
    "data": {
        "id": "10",
        "username": "test1234",
        "email": "test123@mailinator.com",
        "mobile_number": "1111111111",
        "bio": "test",
        "link": "https:\/\/www.mysite.com",
        "favorite_book": "test",
        "favorite_genre": "test",
        "title": "Author",
        "title_id": "1",
        "profile_image": "http:\/\/cloud1.kodyinfotech.com:7000\/redwriter\/public\/uploads\/media\/53534670e278f062511d0b522b6372f0.jpg",
        "country": {
            "id": "1",
            "name": "USA",
            "code": "+1",
            "flag": "http:\/\/cloud1.kodyinfotech.com:7000\/redwriter\/public\/flags\/usa.png"
        },
        "state": {
            "id": "1",
            "country_id": "1",
            "country_name": "USA",
            "name": "California"
        },
        "city": {
            "id": "1",
            "state_id": "1",
            "state_name": "California",
            "name": "Los Angeles"
        },
        "email_verified": "1",
        "phone_verified": "1",
        "is_social_login": "normal",
        "friends": "0",
        "my_subscription": {
            "id": "1",
            "name": "Black",
            "type": "Public",
            "price": "100"
        }
    }
}
```
<div id="execution-results-POSTapi-customer-signup" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-signup"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-signup"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-signup" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-signup"></code></pre>
</div>
<form id="form-POSTapi-customer-signup" data-method="POST" data-path="api/customer/signup" data-authed="0" data-hasfiles="3" data-headers='{"Content-Type":"multipart\/form-data","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-signup', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-signup" onclick="tryItOut('POSTapi-customer-signup');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-signup" onclick="cancelTryOut('POSTapi-customer-signup');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-signup" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/signup</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>first_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="first_name" data-endpoint="POSTapi-customer-signup" data-component="body" required  hidden>
<br>
Full name.
</p>
<p>
<b><code>profile_image</code></b>&nbsp;&nbsp;<small>file</small>  &nbsp;
<input type="file" name="profile_image" data-endpoint="POSTapi-customer-signup" data-component="body" required  hidden>
<br>
Profile Image.
</p>
<p>
<b><code>company_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="company_name" data-endpoint="POSTapi-customer-signup" data-component="body" required  hidden>
<br>
Full name.
</p>
<p>
<b><code>company_images[]</code></b>&nbsp;&nbsp;<small>file</small>  &nbsp;
<input type="file" name="company_images.0" data-endpoint="POSTapi-customer-signup" data-component="body" required  hidden>
<br>
Company Images.
</p>
<p>
<b><code>company_documents[]</code></b>&nbsp;&nbsp;<small>file</small>  &nbsp;
<input type="file" name="company_documents.0" data-endpoint="POSTapi-customer-signup" data-component="body" required  hidden>
<br>
Company Documents.
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-customer-signup" data-component="body"  hidden>
<br>
optional max:190 Email.
</p>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-signup" data-component="body" required  hidden>
<br>
max:10  Mobile Number.
</p>
<p>
<b><code>password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="password" data-endpoint="POSTapi-customer-signup" data-component="body" required  hidden>
<br>
min:8 max:16  Password.
</p>
<p>
<b><code>referral_code</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="referral_code" data-endpoint="POSTapi-customer-signup" data-component="body" required  hidden>
<br>
min:8 max:8  Referral Code.
</p>
<p>
<b><code>device_type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="device_type" data-endpoint="POSTapi-customer-signup" data-component="body" required  hidden>
<br>
User's device type. Enums : iphone, android.
</p>
<p>
<b><code>device_token</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="device_token" data-endpoint="POSTapi-customer-signup" data-component="body" required  hidden>
<br>
User's device token.
</p>

</form>


## Auth: Mobile OTP Verify




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/verify_otp" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"otp":"123456","user_id":"1","mobile_number":"1234567890"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/verify_otp"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "otp": "123456",
    "user_id": "1",
    "mobile_number": "1234567890"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Your Mobile number verified successfully",
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMjA4NmYxODJjOGZjNmNlYzI0ZDdjYzQ3NzlmYjFjY2FmZjcwNGE2NWZjMmQxNmY1OTNmNjUyY2FkM2YzYjk0M2ViZDg3ZmRmZGIxZTMwMzYiLCJpYXQiOjE2N...",
        "id": "9",
        "first_name": "John",
        "last_name": "",
        "email": "John@gmail.com",
        "mobile_number": "1234567890",
        "profile_image": "http:\/\/localhost\/ssgc-web\/public\/customer_avtar.jpg",
        "country": {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        },
        "referral_code": "5X9PFTIR",
        "email_verified": "0",
        "phone_verified": "1",
        "is_social_login": "normal"
    }
}
```
<div id="execution-results-POSTapi-customer-verify_otp" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-verify_otp"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-verify_otp"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-verify_otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-verify_otp"></code></pre>
</div>
<form id="form-POSTapi-customer-verify_otp" data-method="POST" data-path="api/customer/verify_otp" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-verify_otp', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-verify_otp" onclick="tryItOut('POSTapi-customer-verify_otp');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-verify_otp" onclick="cancelTryOut('POSTapi-customer-verify_otp');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-verify_otp" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/verify_otp</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>otp</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="otp" data-endpoint="POSTapi-customer-verify_otp" data-component="body" required  hidden>
<br>
max:6  OTP.
</p>
<p>
<b><code>user_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="user_id" data-endpoint="POSTapi-customer-verify_otp" data-component="body" required  hidden>
<br>
User ID.
</p>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-verify_otp" data-component="body" required  hidden>
<br>
max:10  Mobile Number.
</p>

</form>


## Auth: Resend OTP




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/resend_otp" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"user_id":"1","mobile_number":"123456789"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/resend_otp"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "user_id": "1",
    "mobile_number": "123456789"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "OTP sent to your mobile number 9727332489"
}
```
<div id="execution-results-POSTapi-customer-resend_otp" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-resend_otp"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-resend_otp"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-resend_otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-resend_otp"></code></pre>
</div>
<form id="form-POSTapi-customer-resend_otp" data-method="POST" data-path="api/customer/resend_otp" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-resend_otp', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-resend_otp" onclick="tryItOut('POSTapi-customer-resend_otp');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-resend_otp" onclick="cancelTryOut('POSTapi-customer-resend_otp');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-resend_otp" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/resend_otp</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>user_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="user_id" data-endpoint="POSTapi-customer-resend_otp" data-component="body" required  hidden>
<br>
User ID.
</p>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-resend_otp" data-component="body" required  hidden>
<br>
max:10  Mobile Number.
</p>

</form>


## Auth: Login with OTP




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/login_with_otp" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"mobile_number":"123456789","device_type":"iphone","device_token":"abcd1234"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/login_with_otp"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "mobile_number": "123456789",
    "device_type": "iphone",
    "device_token": "abcd1234"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Your Mobile number verified successfully",
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJ...",
        "id": "1",
        "first_name": "Parth",
        "last_name": "Patel",
        "email": "parth.patel@kodytechnolab.com",
        "mobile_number": "972733248",
        "age": "25",
        "profile_image": "http:\/\/localhost\/beauty-fly\/public\/images\/customer_avtar.png",
        "country": {
            "id": "1",
            "name": "Saudi Arabia",
            "code": "+966",
            "flag": "http:\/\/localhost\/beauty-fly\/public\/flags\/saudi_arebia.png"
        },
        "latitude": "",
        "longitude": "",
        "email_verified": "0",
        "phone_verified": "1",
        "is_social_login": "0"
    }
}
```
<div id="execution-results-POSTapi-customer-login_with_otp" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-login_with_otp"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-login_with_otp"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-login_with_otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-login_with_otp"></code></pre>
</div>
<form id="form-POSTapi-customer-login_with_otp" data-method="POST" data-path="api/customer/login_with_otp" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-login_with_otp', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-login_with_otp" onclick="tryItOut('POSTapi-customer-login_with_otp');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-login_with_otp" onclick="cancelTryOut('POSTapi-customer-login_with_otp');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-login_with_otp" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/login_with_otp</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-login_with_otp" data-component="body" required  hidden>
<br>
max:10  Mobile Number.
</p>
<p>
<b><code>device_type</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="device_type" data-endpoint="POSTapi-customer-login_with_otp" data-component="body"  hidden>
<br>
optional User's device type. Enums : iphone, android.
</p>
<p>
<b><code>device_token</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="device_token" data-endpoint="POSTapi-customer-login_with_otp" data-component="body"  hidden>
<br>
optional User's device token.
</p>

</form>


## Auth: Login with password (email / mobile)




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/login_with_password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"email":"john@mail.com","mobile_number":"123456789","password":"12345678","device_type":"iphone","device_token":"abcd1234"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/login_with_password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "email": "john@mail.com",
    "mobile_number": "123456789",
    "password": "12345678",
    "device_type": "iphone",
    "device_token": "abcd1234"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "User logged in successfully.",
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZDI3MDg1NTBhNjZkZjc5ZTQwZmI4MWQ0....",
        "id": "10",
        "first_name": "test",
        "last_name": "",
        "email": "tesssttt@mailinator.com",
        "mobile_number": "1112223331",
        "profile_image": "http:\/\/localhost\/ssgc-web\/public\/customer_avtar.jpg",
        "country": {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        },
        "referral_code": "NRSW80WZ",
        "email_verified": "1",
        "phone_verified": "1",
        "is_social_login": "normal"
    }
}
```
<div id="execution-results-POSTapi-customer-login_with_password" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-login_with_password"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-login_with_password"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-login_with_password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-login_with_password"></code></pre>
</div>
<form id="form-POSTapi-customer-login_with_password" data-method="POST" data-path="api/customer/login_with_password" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-login_with_password', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-login_with_password" onclick="tryItOut('POSTapi-customer-login_with_password');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-login_with_password" onclick="cancelTryOut('POSTapi-customer-login_with_password');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-login_with_password" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/login_with_password</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-customer-login_with_password" data-component="body"  hidden>
<br>
optional Email(required without mobile number).
</p>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-login_with_password" data-component="body"  hidden>
<br>
optional  max:10  Mobile Number(required without email).
</p>
<p>
<b><code>password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="password" data-endpoint="POSTapi-customer-login_with_password" data-component="body" required  hidden>
<br>
min:8 max:16  Password.
</p>
<p>
<b><code>device_type</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="device_type" data-endpoint="POSTapi-customer-login_with_password" data-component="body"  hidden>
<br>
optional User's device type. Enums : iphone, android.
</p>
<p>
<b><code>device_token</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="device_token" data-endpoint="POSTapi-customer-login_with_password" data-component="body"  hidden>
<br>
optional User's device token.
</p>

</form>


## Auth: Forgot Password




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/forgot_password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"send_in":"mobile","email":"test@mail.com","mobile_number":"123456789"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/forgot_password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "send_in": "mobile",
    "email": "test@mail.com",
    "mobile_number": "123456789"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "OTP sent to your mobile number 1112223331"
}
```
<div id="execution-results-POSTapi-customer-forgot_password" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-forgot_password"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-forgot_password"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-forgot_password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-forgot_password"></code></pre>
</div>
<form id="form-POSTapi-customer-forgot_password" data-method="POST" data-path="api/customer/forgot_password" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-forgot_password', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-forgot_password" onclick="tryItOut('POSTapi-customer-forgot_password');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-forgot_password" onclick="cancelTryOut('POSTapi-customer-forgot_password');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-forgot_password" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/forgot_password</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>send_in</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="send_in" data-endpoint="POSTapi-customer-forgot_password" data-component="body" required  hidden>
<br>
send in (values: mobile, email).
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-customer-forgot_password" data-component="body"  hidden>
<br>
optional Email (required if send in email).
</p>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-forgot_password" data-component="body"  hidden>
<br>
optional  max:10  Mobile Number  (required if send in mobile).
</p>

</form>


## Auth: Forgot Password Verify OTP




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/forgot_password_verify_otp" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"mobile_number":"123456789","otp":"1234"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/forgot_password_verify_otp"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "mobile_number": "123456789",
    "otp": "1234"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "OTP has been verified successfully."
}
```
<div id="execution-results-POSTapi-customer-forgot_password_verify_otp" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-forgot_password_verify_otp"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-forgot_password_verify_otp"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-forgot_password_verify_otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-forgot_password_verify_otp"></code></pre>
</div>
<form id="form-POSTapi-customer-forgot_password_verify_otp" data-method="POST" data-path="api/customer/forgot_password_verify_otp" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-forgot_password_verify_otp', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-forgot_password_verify_otp" onclick="tryItOut('POSTapi-customer-forgot_password_verify_otp');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-forgot_password_verify_otp" onclick="cancelTryOut('POSTapi-customer-forgot_password_verify_otp');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-forgot_password_verify_otp" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/forgot_password_verify_otp</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-forgot_password_verify_otp" data-component="body"  hidden>
<br>
optional  max:10  Mobile Number(required if send in mobile).
</p>
<p>
<b><code>otp</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="otp" data-endpoint="POSTapi-customer-forgot_password_verify_otp" data-component="body" required  hidden>
<br>
OTP.
</p>

</form>


## Auth: Forgot Password Resend OTP




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/forgot_password_resend_otp" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"mobile_number":"123456789"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/forgot_password_resend_otp"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "mobile_number": "123456789"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "OTP sent to your mobile number 9727332489"
}
```
<div id="execution-results-POSTapi-customer-forgot_password_resend_otp" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-forgot_password_resend_otp"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-forgot_password_resend_otp"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-forgot_password_resend_otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-forgot_password_resend_otp"></code></pre>
</div>
<form id="form-POSTapi-customer-forgot_password_resend_otp" data-method="POST" data-path="api/customer/forgot_password_resend_otp" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-forgot_password_resend_otp', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-forgot_password_resend_otp" onclick="tryItOut('POSTapi-customer-forgot_password_resend_otp');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-forgot_password_resend_otp" onclick="cancelTryOut('POSTapi-customer-forgot_password_resend_otp');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-forgot_password_resend_otp" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/forgot_password_resend_otp</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-forgot_password_resend_otp" data-component="body"  hidden>
<br>
optional  max:10  Mobile Number(required if send in mobile).
</p>

</form>


## Auth: Reset Password




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/reset_password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"otp":"1234","mobile_number":"pariatur","password":"12345678","confirm_password":"12345678"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/reset_password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "otp": "1234",
    "mobile_number": "pariatur",
    "password": "12345678",
    "confirm_password": "12345678"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Password Reset successfully."
}
```
<div id="execution-results-POSTapi-customer-reset_password" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-reset_password"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-reset_password"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-reset_password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-reset_password"></code></pre>
</div>
<form id="form-POSTapi-customer-reset_password" data-method="POST" data-path="api/customer/reset_password" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-reset_password', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-reset_password" onclick="tryItOut('POSTapi-customer-reset_password');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-reset_password" onclick="cancelTryOut('POSTapi-customer-reset_password');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-reset_password" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/reset_password</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>otp</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="otp" data-endpoint="POSTapi-customer-reset_password" data-component="body" required  hidden>
<br>
OTP.
</p>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>optional</small>     <i>optional</i> &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-reset_password" data-component="body"  hidden>
<br>
max:10  Mobile NumberExample:123456789
</p>
<p>
<b><code>password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="password" data-endpoint="POSTapi-customer-reset_password" data-component="body" required  hidden>
<br>
Password.
</p>
<p>
<b><code>confirm_password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="confirm_password" data-endpoint="POSTapi-customer-reset_password" data-component="body" required  hidden>
<br>
Confirm Password.
</p>

</form>


## Master: States




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/states" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/states"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Country List Found",
    "data": [
        {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        }
    ]
}
```
<div id="execution-results-GETapi-customer-states" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-states"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-states"></code></pre>
</div>
<div id="execution-error-GETapi-customer-states" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-states"></code></pre>
</div>
<form id="form-GETapi-customer-states" data-method="GET" data-path="api/customer/states" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-states', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-states" onclick="tryItOut('GETapi-customer-states');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-states" onclick="cancelTryOut('GETapi-customer-states');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-states" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/states</code></b>
</p>
</form>


## Master: Cities




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/cities/perspiciatis" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/cities/perspiciatis"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Country List Found",
    "data": [
        {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        }
    ]
}
```
<div id="execution-results-GETapi-customer-cities--state_id--" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-cities--state_id--"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-cities--state_id--"></code></pre>
</div>
<div id="execution-error-GETapi-customer-cities--state_id--" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-cities--state_id--"></code></pre>
</div>
<form id="form-GETapi-customer-cities--state_id--" data-method="GET" data-path="api/customer/cities/{state_id?}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-cities--state_id--', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-cities--state_id--" onclick="tryItOut('GETapi-customer-cities--state_id--');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-cities--state_id--" onclick="cancelTryOut('GETapi-customer-cities--state_id--');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-cities--state_id--" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/cities/{state_id?}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>state_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="state_id" data-endpoint="GETapi-customer-cities--state_id--" data-component="url"  hidden>
<br>

</p>
</form>


## Master: Postcodes




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/postcodes/ullam" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/postcodes/ullam"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Country List Found",
    "data": [
        {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        }
    ]
}
```
<div id="execution-results-GETapi-customer-postcodes--city_id--" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-postcodes--city_id--"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-postcodes--city_id--"></code></pre>
</div>
<div id="execution-error-GETapi-customer-postcodes--city_id--" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-postcodes--city_id--"></code></pre>
</div>
<form id="form-GETapi-customer-postcodes--city_id--" data-method="GET" data-path="api/customer/postcodes/{city_id?}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-postcodes--city_id--', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-postcodes--city_id--" onclick="tryItOut('GETapi-customer-postcodes--city_id--');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-postcodes--city_id--" onclick="cancelTryOut('GETapi-customer-postcodes--city_id--');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-postcodes--city_id--" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/postcodes/{city_id?}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>city_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="city_id" data-endpoint="GETapi-customer-postcodes--city_id--" data-component="url"  hidden>
<br>

</p>
</form>


## Hamburger Menu: CMS




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/cms" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/cms"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "CMS Pages",
    "data": {
        "terms_page_url": "http:\/\/localhost\/ssgc-web\/public\/api\/customer\/terms_and_conditions",
        "about_us": "http:\/\/localhost\/ssgc-web\/public\/api\/customer\/about_us",
        "privacy_policy": "http:\/\/localhost\/ssgc-web\/public\/api\/customer\/privacy_policy"
    }
}
```
<div id="execution-results-GETapi-customer-cms" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-cms"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-cms"></code></pre>
</div>
<div id="execution-error-GETapi-customer-cms" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-cms"></code></pre>
</div>
<form id="form-GETapi-customer-cms" data-method="GET" data-path="api/customer/cms" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-cms', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-cms" onclick="tryItOut('GETapi-customer-cms');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-cms" onclick="cancelTryOut('GETapi-customer-cms');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-cms" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/cms</code></b>
</p>
</form>


## Hamburger Menu: Refer &amp; Earn

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/refer_and_earn" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/refer_and_earn"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data found",
    "data": {
        "referaal_code": "12345678",
        "title": "You Will Get 1000 Points On Your First Referral",
        "content": "This is content for Refer and Earn, Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet."
    }
}
```
<div id="execution-results-GETapi-customer-refer_and_earn" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-refer_and_earn"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-refer_and_earn"></code></pre>
</div>
<div id="execution-error-GETapi-customer-refer_and_earn" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-refer_and_earn"></code></pre>
</div>
<form id="form-GETapi-customer-refer_and_earn" data-method="GET" data-path="api/customer/refer_and_earn" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-refer_and_earn', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-refer_and_earn" onclick="tryItOut('GETapi-customer-refer_and_earn');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-refer_and_earn" onclick="cancelTryOut('GETapi-customer-refer_and_earn');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-refer_and_earn" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/refer_and_earn</code></b>
</p>
<p>
<label id="auth-GETapi-customer-refer_and_earn" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-refer_and_earn" data-component="header"></label>
</p>
</form>


## Books: Book list




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/books_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"business_category_id":"1","language":"hindi","category_id":"3","user_id":"1"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/books_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "business_category_id": "1",
    "language": "hindi",
    "category_id": "3",
    "user_id": "1"
}

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Books available",
    "data": [
        {
            "book_id": "5",
            "name": "gfagfagfag",
            "sale_price": "301.89",
            "mrp": "1111.00",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/avengers-death-infinity-war_16735055604656.jpg",
            "quantity": "5",
            "added_to_cart": "1",
            "cart_item_id": "6"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/books_list?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/books_list?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-GETapi-customer-books_list" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-books_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-books_list"></code></pre>
</div>
<div id="execution-error-GETapi-customer-books_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-books_list"></code></pre>
</div>
<form id="form-GETapi-customer-books_list" data-method="GET" data-path="api/customer/books_list" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-books_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-books_list" onclick="tryItOut('GETapi-customer-books_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-books_list" onclick="cancelTryOut('GETapi-customer-books_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-books_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/books_list</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>business_category_id</code></b>&nbsp;&nbsp;<small>numeric</small>  &nbsp;
<input type="text" name="business_category_id" data-endpoint="GETapi-customer-books_list" data-component="body" required  hidden>
<br>
from id from business_categories API
</p>
<p>
<b><code>language</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="language" data-endpoint="GETapi-customer-books_list" data-component="body" required  hidden>
<br>
Language (hindi,english).
</p>
<p>
<b><code>category_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="category_id" data-endpoint="GETapi-customer-books_list" data-component="body" required  hidden>
<br>
Category_id (category_id or subcategory_id of any level).
</p>
<p>
<b><code>user_id</code></b>&nbsp;&nbsp;<small>numeric</small>     <i>optional</i> &nbsp;
<input type="text" name="user_id" data-endpoint="GETapi-customer-books_list" data-component="body"  hidden>
<br>
optional User id if logged in API
</p>

</form>


## Books: Book Details

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/book_details" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"book_id":"1","user_id":"1"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/book_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "book_id": "1",
    "user_id": "1"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Book details",
    "data": {
        "book_id": "5",
        "name": "gfagfagfag",
        "sub_heading": "agagad",
        "description": "gagagag",
        "additional_info": "agagaga",
        "sale_price": "301.89",
        "mrp": "1111.00",
        "weight": "11",
        "language": "both",
        "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/avengers-death-infinity-war_16735055604656.jpg",
        "cover_images": [
            {
                "id": "9",
                "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/Bedroom-Furniture-Sets_16735055608058.jpg"
            }
        ],
        "added_to_cart": "1",
        "cart_item_id": "6",
        "related_products": [
            {
                "book_id": "4",
                "name": "test  hn",
                "sale_price": "245",
                "mrp": "500.00",
                "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            }
        ]
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": {}
}
```
<div id="execution-results-POSTapi-customer-book_details" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-book_details"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-book_details"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-book_details" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-book_details"></code></pre>
</div>
<form id="form-POSTapi-customer-book_details" data-method="POST" data-path="api/customer/book_details" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-book_details', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-book_details" onclick="tryItOut('POSTapi-customer-book_details');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-book_details" onclick="cancelTryOut('POSTapi-customer-book_details');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-book_details" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/book_details</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-book_details" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-book_details" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>book_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="book_id" data-endpoint="POSTapi-customer-book_details" data-component="body" required  hidden>
<br>
Book Id.
</p>
<p>
<b><code>user_id</code></b>&nbsp;&nbsp;<small>numeric</small>     <i>optional</i> &nbsp;
<input type="text" name="user_id" data-endpoint="POSTapi-customer-book_details" data-component="body"  hidden>
<br>
optional User id if logged in API
</p>

</form>


## Home: Search




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/home/home_search" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"type":"books","search_string":"lorem ipsum"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/home/home_search"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "type": "books",
    "search_string": "lorem ipsum"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "product_id": "5",
            "search_type": "books",
            "name": "gfagfagfag",
            "sale_price": "",
            "mrp": "1111.00",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/avengers-death-infinity-war_16735055604656.jpg",
            "quantity": "0",
            "added_to_cart": "0",
            "cart_item_id": "",
            "stock_status": "in_stock",
            "stock_status_label": "In stock",
            "type": "",
            "type_label": "Books",
            "expiry_date": ""
        },
        {
            "product_id": "4",
            "search_type": "books",
            "name": "test  hn",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
            "quantity": "0",
            "added_to_cart": "0",
            "cart_item_id": "",
            "stock_status": "out_of_stock",
            "stock_status_label": "Out of stock",
            "type": "",
            "type_label": "Books",
            "expiry_date": ""
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/home\/home_search?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/home\/home_search?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 2
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-POSTapi-customer-home-home_search" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-home-home_search"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-home-home_search"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-home-home_search" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-home-home_search"></code></pre>
</div>
<form id="form-POSTapi-customer-home-home_search" data-method="POST" data-path="api/customer/home/home_search" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-home-home_search', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-home-home_search" onclick="tryItOut('POSTapi-customer-home-home_search');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-home-home_search" onclick="cancelTryOut('POSTapi-customer-home-home_search');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-home-home_search" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/home/home_search</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="POSTapi-customer-home-home_search" data-component="body" required  hidden>
<br>
search type enum:books,coupons
</p>
<p>
<b><code>search_string</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="search_string" data-endpoint="POSTapi-customer-home-home_search" data-component="body" required  hidden>
<br>
search data
</p>

</form>


## Home: Trending Books List




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/home/trending_book_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/home/trending_book_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "book_id": "20",
            "name": "the Bible",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740394854126.png",
            "quantity": "0",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "18",
            "name": "THE SOUND AND THE FURY",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740392737976.png",
            "quantity": "0",
            "added_to_cart": "0",
            "cart_item_id": ""
        }
    ],
    "links": {
        "first": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/api\/customer\/home\/trending_book_list?page=1",
        "last": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/api\/customer\/home\/trending_book_list?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 20,
        "total": 2
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-GETapi-customer-home-trending_book_list" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-home-trending_book_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-home-trending_book_list"></code></pre>
</div>
<div id="execution-error-GETapi-customer-home-trending_book_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-home-trending_book_list"></code></pre>
</div>
<form id="form-GETapi-customer-home-trending_book_list" data-method="GET" data-path="api/customer/home/trending_book_list" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-home-trending_book_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-home-trending_book_list" onclick="tryItOut('GETapi-customer-home-trending_book_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-home-trending_book_list" onclick="cancelTryOut('GETapi-customer-home-trending_book_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-home-trending_book_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/home/trending_book_list</code></b>
</p>
</form>


## Home: Business Category Sections Data




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/home/business_category_section_data" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/home/business_category_section_data"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "business_category_id": "1",
            "category_name": "bb",
            "type": "books",
            "list": [
                {
                    "product_id": "5",
                    "search_type": "",
                    "name": "gfagfagfag",
                    "sale_price": "200.89",
                    "mrp": "1111.00",
                    "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/avengers-death-infinity-war_16735055604656.jpg",
                    "quantity": "0",
                    "added_to_cart": "0",
                    "cart_item_id": "",
                    "stock_status": "in_stock",
                    "stock_status_label": "In stock",
                    "type": "",
                    "type_label": "",
                    "expiry_date": ""
                }
            ],
            "container": []
        }
    ]
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-GETapi-customer-home-business_category_section_data" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-home-business_category_section_data"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-home-business_category_section_data"></code></pre>
</div>
<div id="execution-error-GETapi-customer-home-business_category_section_data" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-home-business_category_section_data"></code></pre>
</div>
<form id="form-GETapi-customer-home-business_category_section_data" data-method="GET" data-path="api/customer/home/business_category_section_data" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-home-business_category_section_data', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-home-business_category_section_data" onclick="tryItOut('GETapi-customer-home-business_category_section_data');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-home-business_category_section_data" onclick="cancelTryOut('GETapi-customer-home-business_category_section_data');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-home-business_category_section_data" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/home/business_category_section_data</code></b>
</p>
</form>


## Auth: Logout

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"device_token":"abcd1234"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "device_token": "abcd1234"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "You have logged-out successfully"
}
```
<div id="execution-results-POSTapi-customer-logout" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-logout"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-logout"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-logout"></code></pre>
</div>
<form id="form-POSTapi-customer-logout" data-method="POST" data-path="api/customer/logout" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-logout', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-logout" onclick="tryItOut('POSTapi-customer-logout');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-logout" onclick="cancelTryOut('POSTapi-customer-logout');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-logout" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/logout</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-logout" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-logout" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>device_token</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="device_token" data-endpoint="POSTapi-customer-logout" data-component="body"  hidden>
<br>
optional User's device token.
</p>

</form>


## Auth: Update Device Token

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/update_device_token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"device_type":"iphone","device_token":"abcd1234"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/update_device_token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "device_type": "iphone",
    "device_token": "abcd1234"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Device details updated."
}
```
<div id="execution-results-POSTapi-customer-update_device_token" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-update_device_token"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-update_device_token"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-update_device_token" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-update_device_token"></code></pre>
</div>
<form id="form-POSTapi-customer-update_device_token" data-method="POST" data-path="api/customer/update_device_token" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-update_device_token', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-update_device_token" onclick="tryItOut('POSTapi-customer-update_device_token');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-update_device_token" onclick="cancelTryOut('POSTapi-customer-update_device_token');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-update_device_token" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/update_device_token</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-update_device_token" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-update_device_token" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>device_type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="device_type" data-endpoint="POSTapi-customer-update_device_token" data-component="body" required  hidden>
<br>
User's device type. Enums : iphone, android.
</p>
<p>
<b><code>device_token</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="device_token" data-endpoint="POSTapi-customer-update_device_token" data-component="body" required  hidden>
<br>
User's device token.
</p>

</form>


## Profile: Profile Details

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/profile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/profile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "My Profile",
    "data": {
        "id": "10",
        "first_name": "test",
        "last_name": "",
        "email": "tesssttt@mailinator.com",
        "mobile_number": "1112223331",
        "profile_image": "http:\/\/localhost\/ssgc-web\/public\/customer_avtar.jpg",
        "country": {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        },
        "referral_code": "NRSW80WZ",
        "email_verified": "1",
        "phone_verified": "1",
        "is_social_login": "normal"
    }
}
```
<div id="execution-results-GETapi-customer-profile" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-profile"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-profile"></code></pre>
</div>
<div id="execution-error-GETapi-customer-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-profile"></code></pre>
</div>
<form id="form-GETapi-customer-profile" data-method="GET" data-path="api/customer/profile" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-profile', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-profile" onclick="tryItOut('GETapi-customer-profile');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-profile" onclick="cancelTryOut('GETapi-customer-profile');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-profile" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/profile</code></b>
</p>
<p>
<label id="auth-GETapi-customer-profile" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-profile" data-component="header"></label>
</p>
</form>


## Profile: Update Personal Details

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/profile/update_personal_details" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"first_name":"John","last_name":"John","company_name":"John"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/profile/update_personal_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "first_name": "John",
    "last_name": "John",
    "company_name": "John"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Profile details has been updated successfully",
    "data": {
        "id": "10",
        "first_name": "test",
        "last_name": "",
        "email": "tesssttt@mailinator.com",
        "mobile_number": "1112223331",
        "profile_image": "http:\/\/localhost\/ssgc-web\/public\/customer_avtar.jpg",
        "country": {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        },
        "referral_code": "NRSW80WZ",
        "email_verified": "1",
        "phone_verified": "1",
        "is_social_login": "normal"
    }
}
```
<div id="execution-results-POSTapi-customer-profile-update_personal_details" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-profile-update_personal_details"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-profile-update_personal_details"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-profile-update_personal_details" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-profile-update_personal_details"></code></pre>
</div>
<form id="form-POSTapi-customer-profile-update_personal_details" data-method="POST" data-path="api/customer/profile/update_personal_details" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-profile-update_personal_details', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-profile-update_personal_details" onclick="tryItOut('POSTapi-customer-profile-update_personal_details');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-profile-update_personal_details" onclick="cancelTryOut('POSTapi-customer-profile-update_personal_details');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-profile-update_personal_details" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/profile/update_personal_details</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-profile-update_personal_details" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-profile-update_personal_details" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>first_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="first_name" data-endpoint="POSTapi-customer-profile-update_personal_details" data-component="body" required  hidden>
<br>
max:50  First Name.
</p>
<p>
<b><code>last_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="last_name" data-endpoint="POSTapi-customer-profile-update_personal_details" data-component="body"  hidden>
<br>
optional max:50  Last Name.
</p>
<p>
<b><code>company_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="company_name" data-endpoint="POSTapi-customer-profile-update_personal_details" data-component="body" required  hidden>
<br>
Full name.
</p>

</form>


## Profile: Update Profile Image

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/profile/update_image" \
    -H "Content-Type: multipart/form-data" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -F "profile_image=@C:\Users\ronak.soni\AppData\Local\Temp\php790D.tmp" 
```

```javascript
const url = new URL(
    "http://localhost/api/customer/profile/update_image"
);

let headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
    "Accept-Language": "en",
};

const body = new FormData();
body.append('profile_image', document.querySelector('input[name="profile_image"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Profile details has been updated successfully",
    "data": {
        "id": "10",
        "first_name": "test",
        "last_name": "",
        "email": "tesssttt@mailinator.com",
        "mobile_number": "1112223331",
        "profile_image": "http:\/\/localhost\/ssgc-web\/public\/customer_avtar.jpg",
        "country": {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        },
        "referral_code": "NRSW80WZ",
        "email_verified": "1",
        "phone_verified": "1",
        "is_social_login": "normal"
    }
}
```
<div id="execution-results-POSTapi-customer-profile-update_image" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-profile-update_image"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-profile-update_image"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-profile-update_image" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-profile-update_image"></code></pre>
</div>
<form id="form-POSTapi-customer-profile-update_image" data-method="POST" data-path="api/customer/profile/update_image" data-authed="1" data-hasfiles="1" data-headers='{"Content-Type":"multipart\/form-data","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-profile-update_image', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-profile-update_image" onclick="tryItOut('POSTapi-customer-profile-update_image');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-profile-update_image" onclick="cancelTryOut('POSTapi-customer-profile-update_image');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-profile-update_image" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/profile/update_image</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-profile-update_image" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-profile-update_image" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>profile_image</code></b>&nbsp;&nbsp;<small>file</small>  &nbsp;
<input type="file" name="profile_image" data-endpoint="POSTapi-customer-profile-update_image" data-component="body" required  hidden>
<br>
The image.
</p>

</form>


## Profile: Update Company Docs &amp; Images

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/profile/update_company_images" \
    -H "Content-Type: multipart/form-data" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -F "company_images[]=@C:\Users\ronak.soni\AppData\Local\Temp\php791E.tmp"     -F "company_documents[]=@C:\Users\ronak.soni\AppData\Local\Temp\php791F.tmp" 
```

```javascript
const url = new URL(
    "http://localhost/api/customer/profile/update_company_images"
);

let headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
    "Accept-Language": "en",
};

const body = new FormData();
body.append('company_images[]', document.querySelector('input[name="company_images[]"]').files[0]);
body.append('company_documents[]', document.querySelector('input[name="company_documents[]"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Profile details has been updated successfully",
    "data": {
        "id": "10",
        "first_name": "test",
        "last_name": "",
        "email": "tesssttt@mailinator.com",
        "mobile_number": "1112223331",
        "profile_image": "http:\/\/localhost\/ssgc-web\/public\/customer_avtar.jpg",
        "country": {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        },
        "referral_code": "NRSW80WZ",
        "email_verified": "1",
        "phone_verified": "1",
        "is_social_login": "normal"
    }
}
```
<div id="execution-results-POSTapi-customer-profile-update_company_images" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-profile-update_company_images"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-profile-update_company_images"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-profile-update_company_images" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-profile-update_company_images"></code></pre>
</div>
<form id="form-POSTapi-customer-profile-update_company_images" data-method="POST" data-path="api/customer/profile/update_company_images" data-authed="1" data-hasfiles="2" data-headers='{"Content-Type":"multipart\/form-data","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-profile-update_company_images', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-profile-update_company_images" onclick="tryItOut('POSTapi-customer-profile-update_company_images');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-profile-update_company_images" onclick="cancelTryOut('POSTapi-customer-profile-update_company_images');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-profile-update_company_images" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/profile/update_company_images</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-profile-update_company_images" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-profile-update_company_images" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>company_images[]</code></b>&nbsp;&nbsp;<small>file</small>  &nbsp;
<input type="file" name="company_images.0" data-endpoint="POSTapi-customer-profile-update_company_images" data-component="body" required  hidden>
<br>
Company Images.
</p>
<p>
<b><code>company_documents[]</code></b>&nbsp;&nbsp;<small>file</small>  &nbsp;
<input type="file" name="company_documents.0" data-endpoint="POSTapi-customer-profile-update_company_images" data-component="body" required  hidden>
<br>
Company Documents.
</p>

</form>


## Profile: Update Mobile Number

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/profile/update/mobile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"mobile_number":"1234567890"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/profile/update/mobile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "mobile_number": "1234567890"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "OTP sent to your mobile number 1112223332",
    "data": {
        "id": "10",
        "first_name": "test",
        "last_name": "",
        "email": "tesssttt@mailinator.com",
        "mobile_number": "1112223331",
        "profile_image": "http:\/\/localhost\/ssgc-web\/public\/customer_avtar.jpg",
        "country": {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        },
        "referral_code": "NRSW80WZ",
        "email_verified": "1",
        "phone_verified": "1",
        "is_social_login": "normal"
    }
}
```
<div id="execution-results-POSTapi-customer-profile-update-mobile" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-profile-update-mobile"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-profile-update-mobile"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-profile-update-mobile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-profile-update-mobile"></code></pre>
</div>
<form id="form-POSTapi-customer-profile-update-mobile" data-method="POST" data-path="api/customer/profile/update/mobile" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-profile-update-mobile', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-profile-update-mobile" onclick="tryItOut('POSTapi-customer-profile-update-mobile');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-profile-update-mobile" onclick="cancelTryOut('POSTapi-customer-profile-update-mobile');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-profile-update-mobile" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/profile/update/mobile</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-profile-update-mobile" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-profile-update-mobile" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-profile-update-mobile" data-component="body" required  hidden>
<br>
max:10  Mobile Number.
</p>

</form>


## Profile: Verify Mobile Number

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/profile/verify/mobile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"mobile_number":"123456789","otp":"1234"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/profile/verify/mobile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "mobile_number": "123456789",
    "otp": "1234"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Your mobile number has been updated successfully",
    "data": {
        "id": "10",
        "first_name": "test",
        "last_name": "",
        "email": "tesssttt@mailinator.com",
        "mobile_number": "1112223331",
        "profile_image": "http:\/\/localhost\/ssgc-web\/public\/customer_avtar.jpg",
        "country": {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        },
        "referral_code": "NRSW80WZ",
        "email_verified": "1",
        "phone_verified": "1",
        "is_social_login": "normal"
    }
}
```
<div id="execution-results-POSTapi-customer-profile-verify-mobile" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-profile-verify-mobile"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-profile-verify-mobile"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-profile-verify-mobile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-profile-verify-mobile"></code></pre>
</div>
<form id="form-POSTapi-customer-profile-verify-mobile" data-method="POST" data-path="api/customer/profile/verify/mobile" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-profile-verify-mobile', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-profile-verify-mobile" onclick="tryItOut('POSTapi-customer-profile-verify-mobile');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-profile-verify-mobile" onclick="cancelTryOut('POSTapi-customer-profile-verify-mobile');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-profile-verify-mobile" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/profile/verify/mobile</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-profile-verify-mobile" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-profile-verify-mobile" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-profile-verify-mobile" data-component="body" required  hidden>
<br>
max:10  Mobile Number.
</p>
<p>
<b><code>otp</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="otp" data-endpoint="POSTapi-customer-profile-verify-mobile" data-component="body" required  hidden>
<br>
max:4  OTP.
</p>

</form>


## Profile: Update Email Address

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/profile/update/email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"email":"John@gmail.com"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/profile/update/email"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "email": "John@gmail.com"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "OTP sent to your email testmmm1@mailinator.com",
    "data": {
        "id": "10",
        "first_name": "test",
        "last_name": "",
        "email": "tesssttt@mailinator.com",
        "mobile_number": "1112223331",
        "profile_image": "http:\/\/localhost\/ssgc-web\/public\/customer_avtar.jpg",
        "country": {
            "id": "1",
            "name": "India",
            "code": "+91",
            "flag": "http:\/\/localhost\/ssgc-web\/public\/flags\/india.png"
        },
        "referral_code": "NRSW80WZ",
        "email_verified": "1",
        "phone_verified": "1",
        "is_social_login": "normal"
    }
}
```
<div id="execution-results-POSTapi-customer-profile-update-email" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-profile-update-email"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-profile-update-email"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-profile-update-email" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-profile-update-email"></code></pre>
</div>
<form id="form-POSTapi-customer-profile-update-email" data-method="POST" data-path="api/customer/profile/update/email" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-profile-update-email', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-profile-update-email" onclick="tryItOut('POSTapi-customer-profile-update-email');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-profile-update-email" onclick="cancelTryOut('POSTapi-customer-profile-update-email');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-profile-update-email" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/profile/update/email</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-profile-update-email" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-profile-update-email" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-customer-profile-update-email" data-component="body"  hidden>
<br>
optional max:190  Email.
</p>

</form>


## Profile: Resend Mobile  OTP

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/profile/resend_mobile_otp" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"mobile_number":"123456789"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/profile/resend_mobile_otp"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "mobile_number": "123456789"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "OTP sent to your mobile number 1112223331"
}
```
<div id="execution-results-POSTapi-customer-profile-resend_mobile_otp" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-profile-resend_mobile_otp"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-profile-resend_mobile_otp"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-profile-resend_mobile_otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-profile-resend_mobile_otp"></code></pre>
</div>
<form id="form-POSTapi-customer-profile-resend_mobile_otp" data-method="POST" data-path="api/customer/profile/resend_mobile_otp" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-profile-resend_mobile_otp', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-profile-resend_mobile_otp" onclick="tryItOut('POSTapi-customer-profile-resend_mobile_otp');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-profile-resend_mobile_otp" onclick="cancelTryOut('POSTapi-customer-profile-resend_mobile_otp');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-profile-resend_mobile_otp" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/profile/resend_mobile_otp</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-profile-resend_mobile_otp" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-profile-resend_mobile_otp" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-profile-resend_mobile_otp" data-component="body" required  hidden>
<br>
min:10 max:10  Mobile Number.
</p>

</form>


## Profile: Earn Accounts

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/profile/earn_accounts" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"type":"doloremque"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/profile/earn_accounts"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "type": "doloremque"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": {
        "balance_points": "0",
        "data": [
            {
                "type": "Welcome Points",
                "date": "01 Jul, 2022",
                "points": "10"
            },
            {
                "type": "Redeem Points",
                "date": "01 Jul, 2022",
                "points": "-10"
            }
        ]
    }
}
```
<div id="execution-results-POSTapi-customer-profile-earn_accounts" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-profile-earn_accounts"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-profile-earn_accounts"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-profile-earn_accounts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-profile-earn_accounts"></code></pre>
</div>
<form id="form-POSTapi-customer-profile-earn_accounts" data-method="POST" data-path="api/customer/profile/earn_accounts" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-profile-earn_accounts', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-profile-earn_accounts" onclick="tryItOut('POSTapi-customer-profile-earn_accounts');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-profile-earn_accounts" onclick="cancelTryOut('POSTapi-customer-profile-earn_accounts');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-profile-earn_accounts" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/profile/earn_accounts</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-profile-earn_accounts" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-profile-earn_accounts" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="POSTapi-customer-profile-earn_accounts" data-component="body" required  hidden>
<br>
history,earn,redeem
</p>

</form>


## Profile: Change Password

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/change_password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"old_password":"12345678","new_password":"12345678","confirm_password":"12345678"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/change_password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "old_password": "12345678",
    "new_password": "12345678",
    "confirm_password": "12345678"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Password updated successfully"
}
```
<div id="execution-results-POSTapi-customer-change_password" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-change_password"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-change_password"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-change_password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-change_password"></code></pre>
</div>
<form id="form-POSTapi-customer-change_password" data-method="POST" data-path="api/customer/change_password" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-change_password', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-change_password" onclick="tryItOut('POSTapi-customer-change_password');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-change_password" onclick="cancelTryOut('POSTapi-customer-change_password');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-change_password" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/change_password</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-change_password" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-change_password" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>old_password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="old_password" data-endpoint="POSTapi-customer-change_password" data-component="body" required  hidden>
<br>
Old Password.
</p>
<p>
<b><code>new_password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="new_password" data-endpoint="POSTapi-customer-change_password" data-component="body" required  hidden>
<br>
New Password.
</p>
<p>
<b><code>confirm_password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="confirm_password" data-endpoint="POSTapi-customer-change_password" data-component="body" required  hidden>
<br>
Confirm Password.
</p>

</form>


## Addresses : Address List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/addresses" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/addresses"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Address List",
    "data": [
        {
            "id": "2",
            "contact_name": "test",
            "contact_number": "1111112222",
            "state_id": "1",
            "state_name": "Gujarat",
            "city_id": "1",
            "city_name": "Ahmedabad",
            "postcode_id": "1",
            "postcode": "380026",
            "area": "satellite",
            "house_no": "20",
            "street": "test",
            "landmark": "test",
            "address_type": "Home"
        }
    ]
}
```
<div id="execution-results-GETapi-customer-addresses" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-addresses"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-addresses"></code></pre>
</div>
<div id="execution-error-GETapi-customer-addresses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-addresses"></code></pre>
</div>
<form id="form-GETapi-customer-addresses" data-method="GET" data-path="api/customer/addresses" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-addresses', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-addresses" onclick="tryItOut('GETapi-customer-addresses');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-addresses" onclick="cancelTryOut('GETapi-customer-addresses');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-addresses" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/addresses</code></b>
</p>
<p>
<label id="auth-GETapi-customer-addresses" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-addresses" data-component="header"></label>
</p>
</form>


## Addresses : Add Address

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/address/add" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"contact_name":"test","company_name":"test","contact_number":"1231231231","email":"test@mail.com","state_id":"Gujarat","state":"eligendi","city_id":"1","city":"neque","postcode_id":"1","postcode":"molestias","area":"test","house_no":"11","street":"xxx","landmark":"xxx","address_type":"Home","is_delivery_address":"yes"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/address/add"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "contact_name": "test",
    "company_name": "test",
    "contact_number": "1231231231",
    "email": "test@mail.com",
    "state_id": "Gujarat",
    "state": "eligendi",
    "city_id": "1",
    "city": "neque",
    "postcode_id": "1",
    "postcode": "molestias",
    "area": "test",
    "house_no": "11",
    "street": "xxx",
    "landmark": "xxx",
    "address_type": "Home",
    "is_delivery_address": "yes"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Address has been added successfully",
    "data": [
        {
            "id": "2",
            "contact_name": "test",
            "contact_number": "1111112222",
            "state_id": "1",
            "state_name": "Gujarat",
            "city_id": "1",
            "city_name": "Ahmedabad",
            "postcode_id": "1",
            "postcode": "380026",
            "area": "satellite",
            "house_no": "20",
            "street": "test",
            "landmark": "test",
            "address_type": "Home"
        }
    ]
}
```
<div id="execution-results-POSTapi-customer-address-add" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-address-add"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-address-add"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-address-add" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-address-add"></code></pre>
</div>
<form id="form-POSTapi-customer-address-add" data-method="POST" data-path="api/customer/address/add" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-address-add', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-address-add" onclick="tryItOut('POSTapi-customer-address-add');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-address-add" onclick="cancelTryOut('POSTapi-customer-address-add');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-address-add" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/address/add</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-address-add" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-address-add" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>contact_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="contact_name" data-endpoint="POSTapi-customer-address-add" data-component="body" required  hidden>
<br>
Contact name.
</p>
<p>
<b><code>company_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="company_name" data-endpoint="POSTapi-customer-address-add" data-component="body" required  hidden>
<br>
Company name.
</p>
<p>
<b><code>contact_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="contact_number" data-endpoint="POSTapi-customer-address-add" data-component="body" required  hidden>
<br>
Contact number.
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-customer-address-add" data-component="body" required  hidden>
<br>
Email.
</p>
<p>
<b><code>state_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="state_id" data-endpoint="POSTapi-customer-address-add" data-component="body"  hidden>
<br>
State id.
</p>
<p>
<b><code>state</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="state" data-endpoint="POSTapi-customer-address-add" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>city_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="city_id" data-endpoint="POSTapi-customer-address-add" data-component="body"  hidden>
<br>
City id.
</p>
<p>
<b><code>city</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="city" data-endpoint="POSTapi-customer-address-add" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>postcode_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="postcode_id" data-endpoint="POSTapi-customer-address-add" data-component="body"  hidden>
<br>
PostCode id.
</p>
<p>
<b><code>postcode</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="postcode" data-endpoint="POSTapi-customer-address-add" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>area</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="area" data-endpoint="POSTapi-customer-address-add" data-component="body" required  hidden>
<br>
Area.
</p>
<p>
<b><code>house_no</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="house_no" data-endpoint="POSTapi-customer-address-add" data-component="body" required  hidden>
<br>
House / Street No.
</p>
<p>
<b><code>street</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="street" data-endpoint="POSTapi-customer-address-add" data-component="body" required  hidden>
<br>
Street.
</p>
<p>
<b><code>landmark</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="landmark" data-endpoint="POSTapi-customer-address-add" data-component="body" required  hidden>
<br>
Landmark.
</p>
<p>
<b><code>address_type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="address_type" data-endpoint="POSTapi-customer-address-add" data-component="body" required  hidden>
<br>
Address Type(Home,Office,Other).
</p>
<p>
<b><code>is_delivery_address</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="is_delivery_address" data-endpoint="POSTapi-customer-address-add" data-component="body" required  hidden>
<br>
Is Delivery Address (Default Address) Enum(yes,no).
</p>

</form>


## Addresses : Edit Address

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/address/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"address_id":"1"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/address/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "address_id": "1"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Address Details",
    "data": {
        "id": "1",
        "contact_name": "test",
        "contact_number": "1111112222",
        "state_id": "1",
        "state_name": "Gujarat",
        "city_id": "1",
        "city_name": "Ahmedabad",
        "postcode_id": "1",
        "postcode": "380026",
        "area": "satellite",
        "house_no": "20",
        "street": "test",
        "landmark": "test",
        "address_type": "Home"
    }
}
```
<div id="execution-results-POSTapi-customer-address-edit" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-address-edit"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-address-edit"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-address-edit" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-address-edit"></code></pre>
</div>
<form id="form-POSTapi-customer-address-edit" data-method="POST" data-path="api/customer/address/edit" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-address-edit', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-address-edit" onclick="tryItOut('POSTapi-customer-address-edit');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-address-edit" onclick="cancelTryOut('POSTapi-customer-address-edit');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-address-edit" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/address/edit</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-address-edit" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-address-edit" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>address_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="address_id" data-endpoint="POSTapi-customer-address-edit" data-component="body" required  hidden>
<br>
Address ID.
</p>

</form>


## Addresses : Update Address

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/address/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"address_id":"1","contact_name":"test","company_name":"test","contact_number":"1231231231","email":"test@mail.com","state_id":"Gujarat","state":"nisi","city_id":"1","city":"molestiae","postcode_id":"1","postcode":"autem","area":"test","house_no":"11","street":"xxx","landmark":"xxx","address_type":"Home","is_delivery_address":"yes"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/address/update"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "address_id": "1",
    "contact_name": "test",
    "company_name": "test",
    "contact_number": "1231231231",
    "email": "test@mail.com",
    "state_id": "Gujarat",
    "state": "nisi",
    "city_id": "1",
    "city": "molestiae",
    "postcode_id": "1",
    "postcode": "autem",
    "area": "test",
    "house_no": "11",
    "street": "xxx",
    "landmark": "xxx",
    "address_type": "Home",
    "is_delivery_address": "yes"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Address has been updated successfully",
    "data": [
        {
            "id": "1",
            "contact_name": "sssss",
            "contact_number": "2222222222",
            "state_id": "2",
            "state_name": "Gujarat",
            "city_id": "3",
            "city_name": "Ahmedabad",
            "postcode_id": "3",
            "postcode": "380026",
            "area": "Bandra",
            "house_no": "40",
            "street": "khaugali",
            "landmark": "Taj Hotel",
            "address_type": "Home"
        }
    ]
}
```
<div id="execution-results-POSTapi-customer-address-update" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-address-update"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-address-update"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-address-update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-address-update"></code></pre>
</div>
<form id="form-POSTapi-customer-address-update" data-method="POST" data-path="api/customer/address/update" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-address-update', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-address-update" onclick="tryItOut('POSTapi-customer-address-update');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-address-update" onclick="cancelTryOut('POSTapi-customer-address-update');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-address-update" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/address/update</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-address-update" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-address-update" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>address_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="address_id" data-endpoint="POSTapi-customer-address-update" data-component="body" required  hidden>
<br>
Address ID.
</p>
<p>
<b><code>contact_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="contact_name" data-endpoint="POSTapi-customer-address-update" data-component="body" required  hidden>
<br>
Contact name.
</p>
<p>
<b><code>company_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="company_name" data-endpoint="POSTapi-customer-address-update" data-component="body" required  hidden>
<br>
Company name.
</p>
<p>
<b><code>contact_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="contact_number" data-endpoint="POSTapi-customer-address-update" data-component="body" required  hidden>
<br>
Contact number.
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-customer-address-update" data-component="body" required  hidden>
<br>
Email.
</p>
<p>
<b><code>state_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="state_id" data-endpoint="POSTapi-customer-address-update" data-component="body"  hidden>
<br>
State id.
</p>
<p>
<b><code>state</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="state" data-endpoint="POSTapi-customer-address-update" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>city_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="city_id" data-endpoint="POSTapi-customer-address-update" data-component="body"  hidden>
<br>
City id.
</p>
<p>
<b><code>city</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="city" data-endpoint="POSTapi-customer-address-update" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>postcode_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="postcode_id" data-endpoint="POSTapi-customer-address-update" data-component="body"  hidden>
<br>
PostCode id.
</p>
<p>
<b><code>postcode</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="postcode" data-endpoint="POSTapi-customer-address-update" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>area</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="area" data-endpoint="POSTapi-customer-address-update" data-component="body" required  hidden>
<br>
Area.
</p>
<p>
<b><code>house_no</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="house_no" data-endpoint="POSTapi-customer-address-update" data-component="body" required  hidden>
<br>
House / Street No.
</p>
<p>
<b><code>street</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="street" data-endpoint="POSTapi-customer-address-update" data-component="body" required  hidden>
<br>
Street.
</p>
<p>
<b><code>landmark</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="landmark" data-endpoint="POSTapi-customer-address-update" data-component="body" required  hidden>
<br>
Landmark.
</p>
<p>
<b><code>address_type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="address_type" data-endpoint="POSTapi-customer-address-update" data-component="body" required  hidden>
<br>
Address Type(Home,Office,Other).
</p>
<p>
<b><code>is_delivery_address</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="is_delivery_address" data-endpoint="POSTapi-customer-address-update" data-component="body" required  hidden>
<br>
Is Delivery Address (Default Address) Enum(yes,no).
</p>

</form>


## Addresses : Delete Address

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/address/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"address_id":"1"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/address/delete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "address_id": "1"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Address has been deleted successfully",
    "data": [
        {
            "id": "4",
            "contact_name": "test",
            "contact_number": "1111112222",
            "state_id": "1",
            "state_name": "Gujarat",
            "city_id": "1",
            "city_name": "Ahmedabad",
            "postcode_id": "1",
            "postcode": "380026",
            "area": "Amraiwadi",
            "house_no": "20",
            "street": "test",
            "landmark": "ssasa",
            "address_type": "Home"
        }
    ]
}
```
<div id="execution-results-POSTapi-customer-address-delete" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-address-delete"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-address-delete"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-address-delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-address-delete"></code></pre>
</div>
<form id="form-POSTapi-customer-address-delete" data-method="POST" data-path="api/customer/address/delete" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-address-delete', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-address-delete" onclick="tryItOut('POSTapi-customer-address-delete');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-address-delete" onclick="cancelTryOut('POSTapi-customer-address-delete');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-address-delete" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/address/delete</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-address-delete" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-address-delete" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>address_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="address_id" data-endpoint="POSTapi-customer-address-delete" data-component="body" required  hidden>
<br>
Address ID.
</p>

</form>


## Customer Support: Reasons

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/reason_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/reason_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Reason data found",
    "data": [
        {
            "id": "2",
            "reason": "Need help for How to do payment"
        },
        {
            "id": "4",
            "reason": "I Need help for How to do payment"
        }
    ]
}
```
<div id="execution-results-GETapi-customer-reason_list" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-reason_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-reason_list"></code></pre>
</div>
<div id="execution-error-GETapi-customer-reason_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-reason_list"></code></pre>
</div>
<form id="form-GETapi-customer-reason_list" data-method="GET" data-path="api/customer/reason_list" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-reason_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-reason_list" onclick="tryItOut('GETapi-customer-reason_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-reason_list" onclick="cancelTryOut('GETapi-customer-reason_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-reason_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/reason_list</code></b>
</p>
<p>
<label id="auth-GETapi-customer-reason_list" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-reason_list" data-component="header"></label>
</p>
</form>


## Customer Support: Send ticket

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/send_ticket" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"reason_id":12,"full_name":"neque","email":"fugiat","mobile_number":"1231231231","message":"dolores"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/send_ticket"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "reason_id": 12,
    "full_name": "neque",
    "email": "fugiat",
    "mobile_number": "1231231231",
    "message": "dolores"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Enquiry has been submitted successfully.",
    "data": {
        "ticket_number": "2",
        "date": "22-03-2022",
        "time": "11:09 AM",
        "admin_comment": "",
        "reason": {
            "id": "2",
            "reason": "Need help for How to do payment"
        },
        "message": "wifi not working in mobile application",
        "status": "Pending",
        "status_original": "pending"
    }
}
```
<div id="execution-results-POSTapi-customer-send_ticket" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-send_ticket"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-send_ticket"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-send_ticket" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-send_ticket"></code></pre>
</div>
<form id="form-POSTapi-customer-send_ticket" data-method="POST" data-path="api/customer/send_ticket" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-send_ticket', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-send_ticket" onclick="tryItOut('POSTapi-customer-send_ticket');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-send_ticket" onclick="cancelTryOut('POSTapi-customer-send_ticket');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-send_ticket" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/send_ticket</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-send_ticket" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-send_ticket" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>reason_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="reason_id" data-endpoint="POSTapi-customer-send_ticket" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>full_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="full_name" data-endpoint="POSTapi-customer-send_ticket" data-component="body" required  hidden>
<br>
max:500
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>email</small>  &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-customer-send_ticket" data-component="body" required  hidden>
<br>
max:500
</p>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-send_ticket" data-component="body" required  hidden>
<br>
Contact number.
</p>
<p>
<b><code>message</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="message" data-endpoint="POSTapi-customer-send_ticket" data-component="body" required  hidden>
<br>
max:500
</p>

</form>


## Customer Support: List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/ticket_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/ticket_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Ticket List found",
    "data": [
        {
            "ticket_number": "2",
            "date": "22-03-2022",
            "time": "11:09 AM",
            "admin_comment": "",
            "reason": {
                "id": "2",
                "reason": "Need help for How to do payment"
            },
            "message": "wifi not working in mobile application",
            "status": "Pending",
            "status_original": "pending"
        },
        {
            "ticket_number": "1",
            "date": "22-03-2022",
            "time": "11:09 AM",
            "admin_comment": "",
            "reason": {
                "id": "2",
                "reason": "Need help for How to do payment"
            },
            "message": "wifi not working in mobile application",
            "status": "Pending",
            "status_original": "pending"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/beauty-fly\/public\/api\/customer\/ticket_list?page=1",
        "last": "http:\/\/localhost\/beauty-fly\/public\/api\/customer\/ticket_list?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 2
    }
}
```
<div id="execution-results-GETapi-customer-ticket_list" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-ticket_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-ticket_list"></code></pre>
</div>
<div id="execution-error-GETapi-customer-ticket_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-ticket_list"></code></pre>
</div>
<form id="form-GETapi-customer-ticket_list" data-method="GET" data-path="api/customer/ticket_list" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-ticket_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-ticket_list" onclick="tryItOut('GETapi-customer-ticket_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-ticket_list" onclick="cancelTryOut('GETapi-customer-ticket_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-ticket_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/ticket_list</code></b>
</p>
<p>
<label id="auth-GETapi-customer-ticket_list" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-ticket_list" data-component="header"></label>
</p>
</form>


## Customer Support: Ticket details

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/ticket/officiis" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/ticket/officiis"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": {
        "ticket_number": "2",
        "date": "22-03-2022",
        "time": "11:09 AM",
        "admin_comment": "",
        "reason": {
            "id": "2",
            "reason": "Need help for How to do payment"
        },
        "message": "wifi not working in mobile application",
        "status": "Pending",
        "status_original": "pending"
    }
}
```
<div id="execution-results-GETapi-customer-ticket--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-ticket--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-ticket--id-"></code></pre>
</div>
<div id="execution-error-GETapi-customer-ticket--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-ticket--id-"></code></pre>
</div>
<form id="form-GETapi-customer-ticket--id-" data-method="GET" data-path="api/customer/ticket/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-ticket--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-ticket--id-" onclick="tryItOut('GETapi-customer-ticket--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-ticket--id-" onclick="cancelTryOut('GETapi-customer-ticket--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-ticket--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/ticket/{id}</code></b>
</p>
<p>
<label id="auth-GETapi-customer-ticket--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-ticket--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-customer-ticket--id-" data-component="url" required  hidden>
<br>

</p>
</form>


## Notifications: List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/notification_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/notification_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "Cart data",
"data": [
{
"id": "9",
},
{
"id": "9",
},
{
"id": "9",
},
]
}
```
<div id="execution-results-GETapi-customer-notification_list" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-notification_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-notification_list"></code></pre>
</div>
<div id="execution-error-GETapi-customer-notification_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-notification_list"></code></pre>
</div>
<form id="form-GETapi-customer-notification_list" data-method="GET" data-path="api/customer/notification_list" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-notification_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-notification_list" onclick="tryItOut('GETapi-customer-notification_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-notification_list" onclick="cancelTryOut('GETapi-customer-notification_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-notification_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/notification_list</code></b>
</p>
<p>
<label id="auth-GETapi-customer-notification_list" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-notification_list" data-component="header"></label>
</p>
</form>


## Notifications: Delete Notification

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/delete_notification" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"id":18594210.2828}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/delete_notification"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "id": 18594210.2828
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "Notification deleted",
}
```
<div id="execution-results-POSTapi-customer-delete_notification" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-delete_notification"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-delete_notification"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-delete_notification" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-delete_notification"></code></pre>
</div>
<form id="form-POSTapi-customer-delete_notification" data-method="POST" data-path="api/customer/delete_notification" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-delete_notification', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-delete_notification" onclick="tryItOut('POSTapi-customer-delete_notification');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-delete_notification" onclick="cancelTryOut('POSTapi-customer-delete_notification');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-delete_notification" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/delete_notification</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-delete_notification" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-delete_notification" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-customer-delete_notification" data-component="body"  hidden>
<br>

</p>

</form>


## Notifications: Clear all Notifications

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/clear_all_notifications" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/clear_all_notifications"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "All notifications have been cleared successfully."
}
```
<div id="execution-results-GETapi-customer-clear_all_notifications" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-clear_all_notifications"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-clear_all_notifications"></code></pre>
</div>
<div id="execution-error-GETapi-customer-clear_all_notifications" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-clear_all_notifications"></code></pre>
</div>
<form id="form-GETapi-customer-clear_all_notifications" data-method="GET" data-path="api/customer/clear_all_notifications" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-clear_all_notifications', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-clear_all_notifications" onclick="tryItOut('GETapi-customer-clear_all_notifications');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-clear_all_notifications" onclick="cancelTryOut('GETapi-customer-clear_all_notifications');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-clear_all_notifications" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/clear_all_notifications</code></b>
</p>
<p>
<label id="auth-GETapi-customer-clear_all_notifications" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-clear_all_notifications" data-component="header"></label>
</p>
</form>


## Notifications: Unread Count

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/notification_count" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/notification_count"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "Cart data",
"data": {
"count": "9",
}
}
```
<div id="execution-results-GETapi-customer-notification_count" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-notification_count"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-notification_count"></code></pre>
</div>
<div id="execution-error-GETapi-customer-notification_count" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-notification_count"></code></pre>
</div>
<form id="form-GETapi-customer-notification_count" data-method="GET" data-path="api/customer/notification_count" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-notification_count', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-notification_count" onclick="tryItOut('GETapi-customer-notification_count');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-notification_count" onclick="cancelTryOut('GETapi-customer-notification_count');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-notification_count" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/notification_count</code></b>
</p>
<p>
<label id="auth-GETapi-customer-notification_count" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-notification_count" data-component="header"></label>
</p>
</form>


## Cart &amp; Checkout: Add To Cart

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/books/add_to_cart" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"book_id":79829.03,"quantity":7.2}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/books/add_to_cart"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "book_id": 79829.03,
    "quantity": 7.2
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "Item has been added to cart",
}
```
<div id="execution-results-POSTapi-customer-books-add_to_cart" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-books-add_to_cart"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-books-add_to_cart"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-books-add_to_cart" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-books-add_to_cart"></code></pre>
</div>
<form id="form-POSTapi-customer-books-add_to_cart" data-method="POST" data-path="api/customer/books/add_to_cart" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-books-add_to_cart', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-books-add_to_cart" onclick="tryItOut('POSTapi-customer-books-add_to_cart');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-books-add_to_cart" onclick="cancelTryOut('POSTapi-customer-books-add_to_cart');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-books-add_to_cart" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/books/add_to_cart</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-books-add_to_cart" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-books-add_to_cart" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>book_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="book_id" data-endpoint="POSTapi-customer-books-add_to_cart" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>quantity</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="quantity" data-endpoint="POSTapi-customer-books-add_to_cart" data-component="body" required  hidden>
<br>

</p>

</form>


## Cart &amp; Checkout: Update Quantity

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/books/update_quantity" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"cart_item_id":5734314.65891593,"quantity":1513357.002}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/books/update_quantity"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "cart_item_id": 5734314.65891593,
    "quantity": 1513357.002
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "Quantity updated successfully",
}
```
<div id="execution-results-POSTapi-customer-books-update_quantity" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-books-update_quantity"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-books-update_quantity"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-books-update_quantity" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-books-update_quantity"></code></pre>
</div>
<form id="form-POSTapi-customer-books-update_quantity" data-method="POST" data-path="api/customer/books/update_quantity" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-books-update_quantity', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-books-update_quantity" onclick="tryItOut('POSTapi-customer-books-update_quantity');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-books-update_quantity" onclick="cancelTryOut('POSTapi-customer-books-update_quantity');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-books-update_quantity" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/books/update_quantity</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-books-update_quantity" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-books-update_quantity" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>cart_item_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="cart_item_id" data-endpoint="POSTapi-customer-books-update_quantity" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>quantity</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="quantity" data-endpoint="POSTapi-customer-books-update_quantity" data-component="body" required  hidden>
<br>

</p>

</form>


## Cart &amp; Checkout: My Cart

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/books/my_cart" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/books/my_cart"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Cart data",
    "data": {
        "cart_id": "8",
        "items": [
            {
                "cart_item_id": "9",
                "quantity": "1",
                "item_id": "10",
                "heading": "Highcourt 2022",
                "mrp": "600.00",
                "sale_price": "500.00",
                "cover_image": "http:\/\/localhost\/ssgc\/public\/uploads\/media\/a320ca76bf2eff0eb009566da69212c6.png",
                "rating": "0.0",
                "coupon_id": "1",
                "qr_code": "qwqwqqw",
                "is_favorite": "0"
            },
            {
                "cart_item_id": "10",
                "quantity": "1",
                "item_id": "9",
                "heading": "Test Book Physical Ebbboknow",
                "mrp": "400.00",
                "sale_price": "300.00",
                "cover_image": "http:\/\/localhost\/ssgc\/public\/uploads\/media\/bd47379207043595463a3e2af9b6dd8f.png",
                "rating": "0.0",
                "coupon_id": "1",
                "qr_code": "qwqwqqw",
                "is_favorite": "0"
            }
        ],
        "earned_points": "0",
        "points_formula": "10 coins = 1 Rs.",
        "order_summary": {
            "total_mrp": "1800.00",
            "discount_on_sale": "450.00",
            "delivery_charges": "40.00",
            "coin_point_discount": "0.00",
            "total_amount": "1390.00"
        },
        "address_required": "1",
        "payment_methods": [
            "payu",
            "ccavenue",
            "cod"
        ],
        "points_redeemed": "1"
    }
}
```
<div id="execution-results-GETapi-customer-books-my_cart" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-books-my_cart"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-books-my_cart"></code></pre>
</div>
<div id="execution-error-GETapi-customer-books-my_cart" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-books-my_cart"></code></pre>
</div>
<form id="form-GETapi-customer-books-my_cart" data-method="GET" data-path="api/customer/books/my_cart" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-books-my_cart', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-books-my_cart" onclick="tryItOut('GETapi-customer-books-my_cart');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-books-my_cart" onclick="cancelTryOut('GETapi-customer-books-my_cart');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-books-my_cart" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/books/my_cart</code></b>
</p>
<p>
<label id="auth-GETapi-customer-books-my_cart" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-books-my_cart" data-component="header"></label>
</p>
</form>


## Cart &amp; Checkout: Update Cart Summary

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/books/update_cart_summary" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"checkout_items":"[1,2]"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/books/update_cart_summary"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "checkout_items": "[1,2]"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": {
        "url": "http:\/\/localhost\/ssgc\/public\/order_payment\/5\/1\/payu"
    }
}
```
<div id="execution-results-POSTapi-customer-books-update_cart_summary" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-books-update_cart_summary"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-books-update_cart_summary"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-books-update_cart_summary" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-books-update_cart_summary"></code></pre>
</div>
<form id="form-POSTapi-customer-books-update_cart_summary" data-method="POST" data-path="api/customer/books/update_cart_summary" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-books-update_cart_summary', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-books-update_cart_summary" onclick="tryItOut('POSTapi-customer-books-update_cart_summary');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-books-update_cart_summary" onclick="cancelTryOut('POSTapi-customer-books-update_cart_summary');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-books-update_cart_summary" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/books/update_cart_summary</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-books-update_cart_summary" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-books-update_cart_summary" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>checkout_items</code></b>&nbsp;&nbsp;<small>array</small>  &nbsp;
<input type="text" name="checkout_items" data-endpoint="POSTapi-customer-books-update_cart_summary" data-component="body" required  hidden>
<br>
array of cart_item_id from Cart & Checkout: My Cart
</p>

</form>


## Cart &amp; Checkout: Checkout

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/books/checkout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"billing_address_id":4084.2,"shipping_address_id":7162.2682,"payment_method":"nihil","checkout_items":"[1,2]"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/books/checkout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "billing_address_id": 4084.2,
    "shipping_address_id": 7162.2682,
    "payment_method": "nihil",
    "checkout_items": "[1,2]"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": {
        "url": "http:\/\/localhost\/ssgc\/public\/order_payment\/5\/1\/payu"
    }
}
```
<div id="execution-results-POSTapi-customer-books-checkout" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-books-checkout"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-books-checkout"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-books-checkout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-books-checkout"></code></pre>
</div>
<form id="form-POSTapi-customer-books-checkout" data-method="POST" data-path="api/customer/books/checkout" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-books-checkout', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-books-checkout" onclick="tryItOut('POSTapi-customer-books-checkout');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-books-checkout" onclick="cancelTryOut('POSTapi-customer-books-checkout');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-books-checkout" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/books/checkout</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-books-checkout" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-books-checkout" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>billing_address_id</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="billing_address_id" data-endpoint="POSTapi-customer-books-checkout" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>shipping_address_id</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="shipping_address_id" data-endpoint="POSTapi-customer-books-checkout" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>payment_method</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="payment_method" data-endpoint="POSTapi-customer-books-checkout" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>checkout_items</code></b>&nbsp;&nbsp;<small>array</small>  &nbsp;
<input type="text" name="checkout_items" data-endpoint="POSTapi-customer-books-checkout" data-component="body" required  hidden>
<br>
array of cart_item_id from Cart & Checkout: My Cart
</p>

</form>


## Coupon Cart &amp; Checkout: Add To Cart

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/coupon/add_to_cart" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"coupon_id":0.5471621,"quantity":6929.152278}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/coupon/add_to_cart"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "coupon_id": 0.5471621,
    "quantity": 6929.152278
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "Item has been added to cart",
}
```
<div id="execution-results-POSTapi-customer-coupon-add_to_cart" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-coupon-add_to_cart"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-coupon-add_to_cart"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-coupon-add_to_cart" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-coupon-add_to_cart"></code></pre>
</div>
<form id="form-POSTapi-customer-coupon-add_to_cart" data-method="POST" data-path="api/customer/coupon/add_to_cart" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-coupon-add_to_cart', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-coupon-add_to_cart" onclick="tryItOut('POSTapi-customer-coupon-add_to_cart');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-coupon-add_to_cart" onclick="cancelTryOut('POSTapi-customer-coupon-add_to_cart');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-coupon-add_to_cart" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/coupon/add_to_cart</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-coupon-add_to_cart" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-coupon-add_to_cart" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>coupon_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="coupon_id" data-endpoint="POSTapi-customer-coupon-add_to_cart" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>quantity</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="quantity" data-endpoint="POSTapi-customer-coupon-add_to_cart" data-component="body" required  hidden>
<br>

</p>

</form>


## Coupon Cart &amp; Checkout: Redeemed Points : apply/remove

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/redeemed_points/magni" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/redeemed_points/magni"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "Cart updated",
}
```
<div id="execution-results-GETapi-customer-redeemed_points--operation-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-redeemed_points--operation-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-redeemed_points--operation-"></code></pre>
</div>
<div id="execution-error-GETapi-customer-redeemed_points--operation-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-redeemed_points--operation-"></code></pre>
</div>
<form id="form-GETapi-customer-redeemed_points--operation-" data-method="GET" data-path="api/customer/redeemed_points/{operation}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-redeemed_points--operation-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-redeemed_points--operation-" onclick="tryItOut('GETapi-customer-redeemed_points--operation-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-redeemed_points--operation-" onclick="cancelTryOut('GETapi-customer-redeemed_points--operation-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-redeemed_points--operation-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/redeemed_points/{operation}</code></b>
</p>
<p>
<label id="auth-GETapi-customer-redeemed_points--operation-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-redeemed_points--operation-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>operation</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="operation" data-endpoint="GETapi-customer-redeemed_points--operation-" data-component="url" required  hidden>
<br>

</p>
</form>


## Coupon Cart &amp; Checkout: Checkout

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/coupon/checkout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"payment_method":"tempore"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/coupon/checkout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "payment_method": "tempore"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": {
        "url": "http:\/\/localhost\/ssgc\/public\/order_payment\/5\/1\/payu"
    }
}
```
<div id="execution-results-POSTapi-customer-coupon-checkout" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-coupon-checkout"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-coupon-checkout"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-coupon-checkout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-coupon-checkout"></code></pre>
</div>
<form id="form-POSTapi-customer-coupon-checkout" data-method="POST" data-path="api/customer/coupon/checkout" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-coupon-checkout', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-coupon-checkout" onclick="tryItOut('POSTapi-customer-coupon-checkout');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-coupon-checkout" onclick="cancelTryOut('POSTapi-customer-coupon-checkout');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-coupon-checkout" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/coupon/checkout</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-coupon-checkout" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-coupon-checkout" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>payment_method</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="payment_method" data-endpoint="POSTapi-customer-coupon-checkout" data-component="body" required  hidden>
<br>

</p>

</form>


## My Orders: My Orders

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/my_orders" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"type":"qui","order_status":"cum"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/my_orders"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "type": "qui",
    "order_status": "cum"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "id": "8",
            "title": "Order #ERER455",
            "image": "http:\/\/localhost\/ssgc\/public\/default_media\/order_image.png",
            "action": "Order ",
            "order_date": "04 Jul 2022"
        },
        {
            "id": "14",
            "title": "Order #ERER455",
            "image": "http:\/\/localhost\/ssgc\/public\/default_media\/order_image.png",
            "action": "Order ",
            "order_date": "05 Jul 2022"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc\/public\/api\/customer\/my_orders?page=1",
        "last": "http:\/\/localhost\/ssgc\/public\/api\/customer\/my_orders?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 5
    }
}
```
<div id="execution-results-POSTapi-customer-my_orders" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-my_orders"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-my_orders"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-my_orders" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-my_orders"></code></pre>
</div>
<form id="form-POSTapi-customer-my_orders" data-method="POST" data-path="api/customer/my_orders" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-my_orders', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-my_orders" onclick="tryItOut('POSTapi-customer-my_orders');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-my_orders" onclick="cancelTryOut('POSTapi-customer-my_orders');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-my_orders" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/my_orders</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-my_orders" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-my_orders" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="POSTapi-customer-my_orders" data-component="body" required  hidden>
<br>
Order Type (Enums : upcoming, past)
</p>
<p>
<b><code>order_status</code></b>&nbsp;&nbsp;<small>array</small>     <i>optional</i> &nbsp;
<input type="text" name="order_status" data-endpoint="POSTapi-customer-my_orders" data-component="body"  hidden>
<br>
Order Status (Enums : on_hold,pending_payment,processing,shipped,completed,cancelled,refunded)
</p>

</form>


## My Orders: Order Detail

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/order_detail/aut" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/order_detail/aut"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": {
        "order_id": "2",
        "order_display": "",
        "order_date": "07 Jul 2022",
        "order_time": "10:15 AM",
        "order_total": "116.50",
        "order_status": "Placed",
        "delivery_address": {
            "id": "60",
            "contact_name": "Parth House",
            "contact_number": "1231231235",
            "state_id": "2",
            "state_name": "Maharashtra",
            "city_id": "3",
            "city_name": "Mumbai",
            "postcode_id": "5",
            "postcode": "380020",
            "area": "Parth House",
            "house_no": "153",
            "street": "Parth House",
            "landmark": "Parth House landmark",
            "address_type": "Home",
            "is_delivery_address": "no"
        },
        "items": [
            {
                "order_item_id": "6",
                "quantity": "1",
                "item_id": "5",
                "item_type": "books",
                "heading": "Test5",
                "mrp": "100.00",
                "sale_price": "90.00",
                "cover_image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc\/public\/uploads\/media\/386d1968e9850353b0f2a3a40b3ae716.png",
                "rating": "0.0"
            }
        ],
        "order_summary": {
            "total_mrp": "100.00",
            "discount_on_sale": "23.50",
            "delivery_charges": "40.00",
            "coin_point_discount": "0.00",
            "total_amount": "116.50"
        }
    }
}
```
<div id="execution-results-GETapi-customer-order_detail--order_id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-order_detail--order_id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-order_detail--order_id-"></code></pre>
</div>
<div id="execution-error-GETapi-customer-order_detail--order_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-order_detail--order_id-"></code></pre>
</div>
<form id="form-GETapi-customer-order_detail--order_id-" data-method="GET" data-path="api/customer/order_detail/{order_id}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-order_detail--order_id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-order_detail--order_id-" onclick="tryItOut('GETapi-customer-order_detail--order_id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-order_detail--order_id-" onclick="cancelTryOut('GETapi-customer-order_detail--order_id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-order_detail--order_id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/order_detail/{order_id}</code></b>
</p>
<p>
<label id="auth-GETapi-customer-order_detail--order_id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-order_detail--order_id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>order_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="order_id" data-endpoint="GETapi-customer-order_detail--order_id-" data-component="url" required  hidden>
<br>

</p>
</form>


## My Digital Coupons: Purchased Coupon List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/my_digital_coupons" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"type":"provident","status":"sint"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/my_digital_coupons"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "type": "provident",
    "status": "sint"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "id": "8",
            "title": "Order #ERER455",
            "image": "http:\/\/localhost\/ssgc\/public\/default_media\/order_image.png",
            "action": "Order ",
            "order_date": "04 Jul 2022"
        },
        {
            "id": "14",
            "title": "Order #ERER455",
            "image": "http:\/\/localhost\/ssgc\/public\/default_media\/order_image.png",
            "action": "Order ",
            "order_date": "05 Jul 2022"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc\/public\/api\/customer\/my_orders?page=1",
        "last": "http:\/\/localhost\/ssgc\/public\/api\/customer\/my_orders?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 5
    }
}
```
<div id="execution-results-POSTapi-customer-my_digital_coupons" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-my_digital_coupons"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-my_digital_coupons"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-my_digital_coupons" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-my_digital_coupons"></code></pre>
</div>
<form id="form-POSTapi-customer-my_digital_coupons" data-method="POST" data-path="api/customer/my_digital_coupons" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-my_digital_coupons', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-my_digital_coupons" onclick="tryItOut('POSTapi-customer-my_digital_coupons');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-my_digital_coupons" onclick="cancelTryOut('POSTapi-customer-my_digital_coupons');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-my_digital_coupons" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/my_digital_coupons</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-my_digital_coupons" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-my_digital_coupons" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="type" data-endpoint="POSTapi-customer-my_digital_coupons" data-component="body"  hidden>
<br>
Order Type (Enums : books, e_books, packages, videos, courses, tests)
</p>
<p>
<b><code>status</code></b>&nbsp;&nbsp;<small>array</small>     <i>optional</i> &nbsp;
<input type="text" name="status" data-endpoint="POSTapi-customer-my_digital_coupons" data-component="body"  hidden>
<br>
Order Status (Enums : processing,completed)
</p>

</form>


## My Digital Coupons: Purchased Coupon Details

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/my_digital_coupon_details/aut" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/my_digital_coupon_details/aut"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": {
        "order_id": "2",
        "order_display": "",
        "order_date": "07 Jul 2022",
        "order_time": "10:15 AM",
        "order_total": "116.50",
        "order_status": "Placed",
        "delivery_address": {
            "id": "60",
            "contact_name": "Parth House",
            "contact_number": "1231231235",
            "state_id": "2",
            "state_name": "Maharashtra",
            "city_id": "3",
            "city_name": "Mumbai",
            "postcode_id": "5",
            "postcode": "380020",
            "area": "Parth House",
            "house_no": "153",
            "street": "Parth House",
            "landmark": "Parth House landmark",
            "address_type": "Home",
            "is_delivery_address": "no"
        },
        "items": [
            {
                "order_item_id": "6",
                "quantity": "1",
                "item_id": "5",
                "item_type": "books",
                "heading": "Test5",
                "mrp": "100.00",
                "sale_price": "90.00",
                "cover_image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc\/public\/uploads\/media\/386d1968e9850353b0f2a3a40b3ae716.png",
                "rating": "0.0"
            }
        ],
        "order_summary": {
            "total_mrp": "100.00",
            "discount_on_sale": "23.50",
            "delivery_charges": "40.00",
            "coin_point_discount": "0.00",
            "total_amount": "116.50"
        }
    }
}
```
<div id="execution-results-GETapi-customer-my_digital_coupon_details--order_id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-my_digital_coupon_details--order_id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-my_digital_coupon_details--order_id-"></code></pre>
</div>
<div id="execution-error-GETapi-customer-my_digital_coupon_details--order_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-my_digital_coupon_details--order_id-"></code></pre>
</div>
<form id="form-GETapi-customer-my_digital_coupon_details--order_id-" data-method="GET" data-path="api/customer/my_digital_coupon_details/{order_id}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-my_digital_coupon_details--order_id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-my_digital_coupon_details--order_id-" onclick="tryItOut('GETapi-customer-my_digital_coupon_details--order_id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-my_digital_coupon_details--order_id-" onclick="cancelTryOut('GETapi-customer-my_digital_coupon_details--order_id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-my_digital_coupon_details--order_id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/my_digital_coupon_details/{order_id}</code></b>
</p>
<p>
<label id="auth-GETapi-customer-my_digital_coupon_details--order_id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-my_digital_coupon_details--order_id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>order_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="order_id" data-endpoint="GETapi-customer-my_digital_coupon_details--order_id-" data-component="url" required  hidden>
<br>

</p>
</form>


## My Digital Coupons: Sale Coupon

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/sale_coupon" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"qr_id":406,"customer_name":"John","customer_contact":"1234567890","sale_price":1199.189}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/sale_coupon"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "qr_id": 406,
    "customer_name": "John",
    "customer_contact": "1234567890",
    "sale_price": 1199.189
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": {}
}
```
<div id="execution-results-POSTapi-customer-sale_coupon" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-sale_coupon"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-sale_coupon"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-sale_coupon" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-sale_coupon"></code></pre>
</div>
<form id="form-POSTapi-customer-sale_coupon" data-method="POST" data-path="api/customer/sale_coupon" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-sale_coupon', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-sale_coupon" onclick="tryItOut('POSTapi-customer-sale_coupon');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-sale_coupon" onclick="cancelTryOut('POSTapi-customer-sale_coupon');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-sale_coupon" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/sale_coupon</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-sale_coupon" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-sale_coupon" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>qr_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="qr_id" data-endpoint="POSTapi-customer-sale_coupon" data-component="body" required  hidden>
<br>
From available coupons array
</p>
<p>
<b><code>customer_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="customer_name" data-endpoint="POSTapi-customer-sale_coupon" data-component="body" required  hidden>
<br>
Full name.
</p>
<p>
<b><code>customer_contact</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="customer_contact" data-endpoint="POSTapi-customer-sale_coupon" data-component="body" required  hidden>
<br>
max:10  Mobile Number.
</p>
<p>
<b><code>sale_price</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="sale_price" data-endpoint="POSTapi-customer-sale_coupon" data-component="body" required  hidden>
<br>

</p>

</form>


## Return: Make My Return List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/my_return/make_my_return_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"language":"hindi","category_id":"3"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/my_return/make_my_return_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "language": "hindi",
    "category_id": "3"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Returnable books available",
    "data": [
        {
            "order_item_id": "2",
            "book_id": "5",
            "order_id": "1",
            "name": "gfagfagfag",
            "sale_price": "-242.50",
            "mrp": "1111.00",
            "quantity": "1",
            "max_returnable_qty": "13",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/avengers-death-infinity-war_16735055604656.jpg",
            "added_to_cart": "0"
        },
        {
            "order_item_id": "1",
            "book_id": "4",
            "order_id": "1",
            "name": "test  hn",
            "sale_price": "-24750.00",
            "mrp": "500.00",
            "quantity": "1",
            "max_returnable_qty": "1",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
            "added_to_cart": "0"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/make_my_return_list?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/make_my_return_list?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 2
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-POSTapi-customer-my_return-make_my_return_list" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-my_return-make_my_return_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-my_return-make_my_return_list"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-my_return-make_my_return_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-my_return-make_my_return_list"></code></pre>
</div>
<form id="form-POSTapi-customer-my_return-make_my_return_list" data-method="POST" data-path="api/customer/my_return/make_my_return_list" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-my_return-make_my_return_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-my_return-make_my_return_list" onclick="tryItOut('POSTapi-customer-my_return-make_my_return_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-my_return-make_my_return_list" onclick="cancelTryOut('POSTapi-customer-my_return-make_my_return_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-my_return-make_my_return_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/my_return/make_my_return_list</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-my_return-make_my_return_list" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-my_return-make_my_return_list" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>language</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="language" data-endpoint="POSTapi-customer-my_return-make_my_return_list" data-component="body" required  hidden>
<br>
Language (hindi,english).
</p>
<p>
<b><code>category_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="category_id" data-endpoint="POSTapi-customer-my_return-make_my_return_list" data-component="body" required  hidden>
<br>
Category_id (category_id or subcategory_id of any level).
</p>

</form>


## Return: Add to return cart

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/my_return/add_to_cart" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"order_item_id":"1","quantity":"3"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/my_return/add_to_cart"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "order_item_id": "1",
    "quantity": "3"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "The product is added into the return cart.",
}
```
<div id="execution-results-POSTapi-customer-my_return-add_to_cart" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-my_return-add_to_cart"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-my_return-add_to_cart"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-my_return-add_to_cart" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-my_return-add_to_cart"></code></pre>
</div>
<form id="form-POSTapi-customer-my_return-add_to_cart" data-method="POST" data-path="api/customer/my_return/add_to_cart" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-my_return-add_to_cart', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-my_return-add_to_cart" onclick="tryItOut('POSTapi-customer-my_return-add_to_cart');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-my_return-add_to_cart" onclick="cancelTryOut('POSTapi-customer-my_return-add_to_cart');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-my_return-add_to_cart" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/my_return/add_to_cart</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-my_return-add_to_cart" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-my_return-add_to_cart" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>order_item_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="order_item_id" data-endpoint="POSTapi-customer-my_return-add_to_cart" data-component="body" required  hidden>
<br>
id from Make My Return List API .
</p>
<p>
<b><code>quantity</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="quantity" data-endpoint="POSTapi-customer-my_return-add_to_cart" data-component="body" required  hidden>
<br>
Quantity.
</p>

</form>


## Return: Update Quantity

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/my_return/update_quantity" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"return_item_id":57138.6336206,"quantity":3536318.415303}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/my_return/update_quantity"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "return_item_id": 57138.6336206,
    "quantity": 3536318.415303
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, cart_empty):

```json
{
    "success": "1",
    "status": "202",
    "message": "Return cart is empty",
    "data": []
}
```
> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "return_item_id": "2",
            "product_id": "4",
            "order_return_id": "2",
            "name": "test  hn",
            "sale_price": "245.00",
            "total_sale_price": "245.00",
            "quantity": "1",
            "purchased_qty": "1",
            "max_returnable_qty": "1",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png"
        }
    ]
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-POSTapi-customer-my_return-update_quantity" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-my_return-update_quantity"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-my_return-update_quantity"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-my_return-update_quantity" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-my_return-update_quantity"></code></pre>
</div>
<form id="form-POSTapi-customer-my_return-update_quantity" data-method="POST" data-path="api/customer/my_return/update_quantity" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-my_return-update_quantity', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-my_return-update_quantity" onclick="tryItOut('POSTapi-customer-my_return-update_quantity');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-my_return-update_quantity" onclick="cancelTryOut('POSTapi-customer-my_return-update_quantity');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-my_return-update_quantity" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/my_return/update_quantity</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-my_return-update_quantity" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-my_return-update_quantity" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>return_item_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="return_item_id" data-endpoint="POSTapi-customer-my_return-update_quantity" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>quantity</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="quantity" data-endpoint="POSTapi-customer-my_return-update_quantity" data-component="body" required  hidden>
<br>

</p>

</form>


## Return: My Return Cart

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/my_return/my_cart" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/my_return/my_cart"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "return_item_id": "2",
            "product_id": "4",
            "order_return_id": "2",
            "name": "test  hn",
            "sale_price": "245.00",
            "total_sale_price": "245.00",
            "quantity": "1",
            "purchased_qty": "1",
            "max_returnable_qty": "1",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png"
        }
    ]
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-GETapi-customer-my_return-my_cart" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-my_return-my_cart"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-my_return-my_cart"></code></pre>
</div>
<div id="execution-error-GETapi-customer-my_return-my_cart" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-my_return-my_cart"></code></pre>
</div>
<form id="form-GETapi-customer-my_return-my_cart" data-method="GET" data-path="api/customer/my_return/my_cart" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-my_return-my_cart', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-my_return-my_cart" onclick="tryItOut('GETapi-customer-my_return-my_cart');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-my_return-my_cart" onclick="cancelTryOut('GETapi-customer-my_return-my_cart');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-my_return-my_cart" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/my_return/my_cart</code></b>
</p>
<p>
<label id="auth-GETapi-customer-my_return-my_cart" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-my_return-my_cart" data-component="header"></label>
</p>
</form>


## Return: Place Order Return

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/my_return/place_order_return" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"return_items":"[1,2]"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/my_return/place_order_return"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "return_items": "[1,2]"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "Order returned successfully.",
}
```
<div id="execution-results-POSTapi-customer-my_return-place_order_return" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-my_return-place_order_return"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-my_return-place_order_return"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-my_return-place_order_return" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-my_return-place_order_return"></code></pre>
</div>
<form id="form-POSTapi-customer-my_return-place_order_return" data-method="POST" data-path="api/customer/my_return/place_order_return" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-my_return-place_order_return', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-my_return-place_order_return" onclick="tryItOut('POSTapi-customer-my_return-place_order_return');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-my_return-place_order_return" onclick="cancelTryOut('POSTapi-customer-my_return-place_order_return');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-my_return-place_order_return" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/my_return/place_order_return</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-my_return-place_order_return" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-my_return-place_order_return" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>return_items</code></b>&nbsp;&nbsp;<small>array</small>  &nbsp;
<input type="text" name="return_items" data-endpoint="POSTapi-customer-my_return-place_order_return" data-component="body" required  hidden>
<br>
array of return_item_id from Return: My Return Cart
</p>

</form>


## Return: Return Product List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/my_return/return_orders_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"status":"return_placed,dispatched,in_review,rejected,accepted"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/my_return/return_orders_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "status": "return_placed,dispatched,in_review,rejected,accepted"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Return orders list.",
    "data": [
        {
            "order_return_id": "5",
            "return_at": "2023-01-19 14:41:21",
            "return_date": "19-01-2023",
            "total_return_quantity": "2",
            "total_sale_price": "546.89",
            "status": "return_placed",
            "status_label": "Return placed"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/my_return\/return_orders_list?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/my_return\/return_orders_list?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-POSTapi-customer-my_return-return_orders_list" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-my_return-return_orders_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-my_return-return_orders_list"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-my_return-return_orders_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-my_return-return_orders_list"></code></pre>
</div>
<form id="form-POSTapi-customer-my_return-return_orders_list" data-method="POST" data-path="api/customer/my_return/return_orders_list" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-my_return-return_orders_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-my_return-return_orders_list" onclick="tryItOut('POSTapi-customer-my_return-return_orders_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-my_return-return_orders_list" onclick="cancelTryOut('POSTapi-customer-my_return-return_orders_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-my_return-return_orders_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/my_return/return_orders_list</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-my_return-return_orders_list" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-my_return-return_orders_list" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>status</code></b>&nbsp;&nbsp;<small>array</small>  &nbsp;
<input type="text" name="status" data-endpoint="POSTapi-customer-my_return-return_orders_list" data-component="body" required  hidden>
<br>
enum:return_placed,dispatched,in_review,rejected,accepted
</p>

</form>


## Return: Dispatch Order Return

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/my_return/dispatch_order_return" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"order_return_id":"1","courier_name":"loremkaka","tracking_number":"123","receipt_image":"1"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/my_return/dispatch_order_return"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "order_return_id": "1",
    "courier_name": "loremkaka",
    "tracking_number": "123",
    "receipt_image": "1"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Return order details.",
    "data": {
        "order_return_id": "5",
        "return_at": "2023-01-19 14:41:21",
        "return_date": "19-01-2023",
        "return_time": "14:41 pm",
        "total_return_quantity": "2",
        "accepted_quantity": "",
        "total_sale_price": "546.89",
        "status": "return_placed",
        "status_label": "Return placed",
        "return_address": "lorem ipsum",
        "receiver_number": "12345678",
        "return_items": [
            {
                "return_item_id": "6",
                "product_id": "4",
                "name": "test  hn",
                "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
                "total_quantity": "1",
                "total_sale_price": "245.00",
                "accepted_quantity": "",
                "rejected_quantity": "",
                "return_sale_price": "0.00"
            },
            {
                "return_item_id": "7",
                "product_id": "5",
                "name": "gfagfagfag",
                "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/avengers-death-infinity-war_16735055604656.jpg",
                "total_quantity": "1",
                "total_sale_price": "301.89",
                "accepted_quantity": "",
                "rejected_quantity": "",
                "return_sale_price": "0.00"
            }
        ]
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": {}
}
```
<div id="execution-results-POSTapi-customer-my_return-dispatch_order_return" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-my_return-dispatch_order_return"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-my_return-dispatch_order_return"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-my_return-dispatch_order_return" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-my_return-dispatch_order_return"></code></pre>
</div>
<form id="form-POSTapi-customer-my_return-dispatch_order_return" data-method="POST" data-path="api/customer/my_return/dispatch_order_return" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-my_return-dispatch_order_return', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-my_return-dispatch_order_return" onclick="tryItOut('POSTapi-customer-my_return-dispatch_order_return');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-my_return-dispatch_order_return" onclick="cancelTryOut('POSTapi-customer-my_return-dispatch_order_return');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-my_return-dispatch_order_return" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/my_return/dispatch_order_return</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-my_return-dispatch_order_return" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-my_return-dispatch_order_return" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>order_return_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="order_return_id" data-endpoint="POSTapi-customer-my_return-dispatch_order_return" data-component="body" required  hidden>
<br>
order_return_id from Return: Return Product List
</p>
<p>
<b><code>courier_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="courier_name" data-endpoint="POSTapi-customer-my_return-dispatch_order_return" data-component="body" required  hidden>
<br>
max:150 Courier service name
</p>
<p>
<b><code>tracking_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="tracking_number" data-endpoint="POSTapi-customer-my_return-dispatch_order_return" data-component="body" required  hidden>
<br>
max:100 Tracking number
</p>
<p>
<b><code>receipt_image</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="receipt_image" data-endpoint="POSTapi-customer-my_return-dispatch_order_return" data-component="body" required  hidden>
<br>
mimes:png,jpg,jpeg,svg max:10mb Receipt Image
</p>

</form>


## Return: Order Return Details

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/my_return/order_return_details" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"order_return_id":"1"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/my_return/order_return_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "order_return_id": "1"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Return order details.",
    "data": {
        "order_return_id": "5",
        "return_at": "2023-01-19 14:41:21",
        "return_date": "19-01-2023",
        "return_time": "14:41 pm",
        "total_return_quantity": "2",
        "accepted_quantity": "",
        "total_sale_price": "546.89",
        "status": "return_placed",
        "status_label": "Return placed",
        "return_address": "lorem ipsum",
        "receiver_number": "12345678",
        "return_items": [
            {
                "return_item_id": "6",
                "product_id": "4",
                "name": "test  hn",
                "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
                "total_quantity": "1",
                "total_sale_price": "245.00",
                "accepted_quantity": "",
                "rejected_quantity": "",
                "return_sale_price": "0.00"
            },
            {
                "return_item_id": "7",
                "product_id": "5",
                "name": "gfagfagfag",
                "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/avengers-death-infinity-war_16735055604656.jpg",
                "total_quantity": "1",
                "total_sale_price": "301.89",
                "accepted_quantity": "",
                "rejected_quantity": "",
                "return_sale_price": "0.00"
            }
        ]
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": {}
}
```
<div id="execution-results-POSTapi-customer-my_return-order_return_details" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-my_return-order_return_details"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-my_return-order_return_details"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-my_return-order_return_details" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-my_return-order_return_details"></code></pre>
</div>
<form id="form-POSTapi-customer-my_return-order_return_details" data-method="POST" data-path="api/customer/my_return/order_return_details" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-my_return-order_return_details', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-my_return-order_return_details" onclick="tryItOut('POSTapi-customer-my_return-order_return_details');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-my_return-order_return_details" onclick="cancelTryOut('POSTapi-customer-my_return-order_return_details');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-my_return-order_return_details" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/my_return/order_return_details</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-my_return-order_return_details" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-my_return-order_return_details" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>order_return_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="order_return_id" data-endpoint="POSTapi-customer-my_return-order_return_details" data-component="body" required  hidden>
<br>
order_return_id from Return: Return Product List
</p>

</form>


## WishList: Out Of Stock Wish List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/wishlist/out_of_stock_wishlist" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/wishlist/out_of_stock_wishlist"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Wish List data found",
    "data": [
        {
            "book_id": "4",
            "name": "test  hn",
            "sub_heading": "head hn",
            "weight": "12",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
            "sale_price": "295",
            "mrp": "500.00",
            "stock_status": "out_of_stock",
            "status_label": "Out of stock",
            "quantity": "1",
            "in_wishlist": "1",
            "wish_list_id": "5"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wishlist\/out_of_stock_wishlist?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wishlist\/out_of_stock_wishlist?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-GETapi-customer-wishlist-out_of_stock_wishlist" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-wishlist-out_of_stock_wishlist"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-wishlist-out_of_stock_wishlist"></code></pre>
</div>
<div id="execution-error-GETapi-customer-wishlist-out_of_stock_wishlist" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-wishlist-out_of_stock_wishlist"></code></pre>
</div>
<form id="form-GETapi-customer-wishlist-out_of_stock_wishlist" data-method="GET" data-path="api/customer/wishlist/out_of_stock_wishlist" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-wishlist-out_of_stock_wishlist', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-wishlist-out_of_stock_wishlist" onclick="tryItOut('GETapi-customer-wishlist-out_of_stock_wishlist');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-wishlist-out_of_stock_wishlist" onclick="cancelTryOut('GETapi-customer-wishlist-out_of_stock_wishlist');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-wishlist-out_of_stock_wishlist" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/wishlist/out_of_stock_wishlist</code></b>
</p>
<p>
<label id="auth-GETapi-customer-wishlist-out_of_stock_wishlist" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-wishlist-out_of_stock_wishlist" data-component="header"></label>
</p>
</form>


## WishList: Wish List(My Wishlist,Available)

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/wishlist/retailer_wishlist" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"type":"my_wishlist"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wishlist/retailer_wishlist"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "type": "my_wishlist"
}

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Wish List data found",
    "data": [
        {
            "wish_list_id": "2",
            "book_id": "4",
            "name": "test  hn",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
            "sale_price": 245,
            "mrp": "500.00",
            "quantity": "1"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wishlist\/retailer_wishlist?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wishlist\/retailer_wishlist?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-GETapi-customer-wishlist-retailer_wishlist" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-wishlist-retailer_wishlist"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-wishlist-retailer_wishlist"></code></pre>
</div>
<div id="execution-error-GETapi-customer-wishlist-retailer_wishlist" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-wishlist-retailer_wishlist"></code></pre>
</div>
<form id="form-GETapi-customer-wishlist-retailer_wishlist" data-method="GET" data-path="api/customer/wishlist/retailer_wishlist" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-wishlist-retailer_wishlist', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-wishlist-retailer_wishlist" onclick="tryItOut('GETapi-customer-wishlist-retailer_wishlist');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-wishlist-retailer_wishlist" onclick="cancelTryOut('GETapi-customer-wishlist-retailer_wishlist');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-wishlist-retailer_wishlist" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/wishlist/retailer_wishlist</code></b>
</p>
<p>
<label id="auth-GETapi-customer-wishlist-retailer_wishlist" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-wishlist-retailer_wishlist" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="GETapi-customer-wishlist-retailer_wishlist" data-component="body" required  hidden>
<br>
Wishlist type enum:my_wishlist,available .
</p>

</form>


## WishList: User&#039;s Dealer List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/wishlist/retailer_dealer_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/wishlist/retailer_dealer_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Dealers found.",
    "data": [
        {
            "dealer_id": "7",
            "first_name": "Moriis",
            "profile_image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/avengers-death-infinity-war_16735099858223.jpg"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wishlist\/retailer_dealer_list?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wishlist\/retailer_dealer_list?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-GETapi-customer-wishlist-retailer_dealer_list" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-wishlist-retailer_dealer_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-wishlist-retailer_dealer_list"></code></pre>
</div>
<div id="execution-error-GETapi-customer-wishlist-retailer_dealer_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-wishlist-retailer_dealer_list"></code></pre>
</div>
<form id="form-GETapi-customer-wishlist-retailer_dealer_list" data-method="GET" data-path="api/customer/wishlist/retailer_dealer_list" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-wishlist-retailer_dealer_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-wishlist-retailer_dealer_list" onclick="tryItOut('GETapi-customer-wishlist-retailer_dealer_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-wishlist-retailer_dealer_list" onclick="cancelTryOut('GETapi-customer-wishlist-retailer_dealer_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-wishlist-retailer_dealer_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/wishlist/retailer_dealer_list</code></b>
</p>
<p>
<label id="auth-GETapi-customer-wishlist-retailer_dealer_list" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-wishlist-retailer_dealer_list" data-component="header"></label>
</p>
</form>


## WishList: Add to Wish List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wishlist/add_to_wishlist" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"book_id":"1","dealers[]":"[3,4]","quantity":"3"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wishlist/add_to_wishlist"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "book_id": "1",
    "dealers[]": "[3,4]",
    "quantity": "3"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "The item is added into the wishlist.",
}
```
<div id="execution-results-POSTapi-customer-wishlist-add_to_wishlist" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wishlist-add_to_wishlist"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wishlist-add_to_wishlist"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wishlist-add_to_wishlist" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wishlist-add_to_wishlist"></code></pre>
</div>
<form id="form-POSTapi-customer-wishlist-add_to_wishlist" data-method="POST" data-path="api/customer/wishlist/add_to_wishlist" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wishlist-add_to_wishlist', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wishlist-add_to_wishlist" onclick="tryItOut('POSTapi-customer-wishlist-add_to_wishlist');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wishlist-add_to_wishlist" onclick="cancelTryOut('POSTapi-customer-wishlist-add_to_wishlist');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wishlist-add_to_wishlist" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wishlist/add_to_wishlist</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wishlist-add_to_wishlist" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wishlist-add_to_wishlist" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>book_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="book_id" data-endpoint="POSTapi-customer-wishlist-add_to_wishlist" data-component="body" required  hidden>
<br>
Book Id .
</p>
<p>
<b><code>dealers[]</code></b>&nbsp;&nbsp;<small>array</small>     <i>optional</i> &nbsp;
<input type="text" name="dealers.0" data-endpoint="POSTapi-customer-wishlist-add_to_wishlist" data-component="body"  hidden>
<br>
optional from WishList: User's Dealer List.
</p>
<p>
<b><code>quantity</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="quantity" data-endpoint="POSTapi-customer-wishlist-add_to_wishlist" data-component="body" required  hidden>
<br>
Quantity.
</p>

</form>


## WishList: Update Quantity

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wishlist/update_quantity" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"wish_list_id":1,"quantity":2}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wishlist/update_quantity"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "wish_list_id": 1,
    "quantity": 2
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "The item quantity is updated.",
}
```
<div id="execution-results-POSTapi-customer-wishlist-update_quantity" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wishlist-update_quantity"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wishlist-update_quantity"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wishlist-update_quantity" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wishlist-update_quantity"></code></pre>
</div>
<form id="form-POSTapi-customer-wishlist-update_quantity" data-method="POST" data-path="api/customer/wishlist/update_quantity" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wishlist-update_quantity', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wishlist-update_quantity" onclick="tryItOut('POSTapi-customer-wishlist-update_quantity');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wishlist-update_quantity" onclick="cancelTryOut('POSTapi-customer-wishlist-update_quantity');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wishlist-update_quantity" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wishlist/update_quantity</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wishlist-update_quantity" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wishlist-update_quantity" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>wish_list_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="wish_list_id" data-endpoint="POSTapi-customer-wishlist-update_quantity" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>quantity</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="quantity" data-endpoint="POSTapi-customer-wishlist-update_quantity" data-component="body" required  hidden>
<br>

</p>

</form>


## WishList: Remove From Wishlist

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wishlist/remove_from_wishlist" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"wish_list_id":1}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wishlist/remove_from_wishlist"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "wish_list_id": 1
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "The item is removed from the wishlist.",
}
```
<div id="execution-results-POSTapi-customer-wishlist-remove_from_wishlist" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wishlist-remove_from_wishlist"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wishlist-remove_from_wishlist"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wishlist-remove_from_wishlist" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wishlist-remove_from_wishlist"></code></pre>
</div>
<form id="form-POSTapi-customer-wishlist-remove_from_wishlist" data-method="POST" data-path="api/customer/wishlist/remove_from_wishlist" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wishlist-remove_from_wishlist', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wishlist-remove_from_wishlist" onclick="tryItOut('POSTapi-customer-wishlist-remove_from_wishlist');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wishlist-remove_from_wishlist" onclick="cancelTryOut('POSTapi-customer-wishlist-remove_from_wishlist');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wishlist-remove_from_wishlist" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wishlist/remove_from_wishlist</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wishlist-remove_from_wishlist" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wishlist-remove_from_wishlist" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>wish_list_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="wish_list_id" data-endpoint="POSTapi-customer-wishlist-remove_from_wishlist" data-component="body" required  hidden>
<br>

</p>

</form>


## WishList: WishList Details

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wishlist/wishlist_details" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"wish_list_id":1}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wishlist/wishlist_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "wish_list_id": 1
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Wish List data found",
    "data": {
        "wish_list_id": "5",
        "book_id": "4",
        "name": "test  hn",
        "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
        "sale_price": "295",
        "mrp": "500.00",
        "quantity": "1",
        "sub_heading": "head hn",
        "weight": "12",
        "status_label": "Out of stock",
        "dealers": [
            {
                "dealer_id": "8",
                "first_name": "dsdsdsd",
                "profile_image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/basque-honey-dining-tables_16742119452273.jpg"
            }
        ]
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": {}
}
```
<div id="execution-results-POSTapi-customer-wishlist-wishlist_details" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wishlist-wishlist_details"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wishlist-wishlist_details"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wishlist-wishlist_details" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wishlist-wishlist_details"></code></pre>
</div>
<form id="form-POSTapi-customer-wishlist-wishlist_details" data-method="POST" data-path="api/customer/wishlist/wishlist_details" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wishlist-wishlist_details', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wishlist-wishlist_details" onclick="tryItOut('POSTapi-customer-wishlist-wishlist_details');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wishlist-wishlist_details" onclick="cancelTryOut('POSTapi-customer-wishlist-wishlist_details');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wishlist-wishlist_details" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wishlist/wishlist_details</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wishlist-wishlist_details" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wishlist-wishlist_details" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>wish_list_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="wish_list_id" data-endpoint="POSTapi-customer-wishlist-wishlist_details" data-component="body" required  hidden>
<br>

</p>

</form>


## WishList: Dealer&#039;s Retailer Wishlist Request

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wishlist/dealer_wishlist_requests" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"search":"lorem"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wishlist/dealer_wishlist_requests"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "search": "lorem"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Wish List data found",
    "data": [
        {
            "wish_list_request_id": "1",
            "retailer_name": "dada",
            "product_name": "test  hn",
            "date": "24-01-2023",
            "time": "13:18 pm",
            "created_at": "2023-01-24T07:48:39.000000Z",
            "quantity": "1"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wishlist\/dealer_wishlist_requests?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wishlist\/dealer_wishlist_requests?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-POSTapi-customer-wishlist-dealer_wishlist_requests" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wishlist-dealer_wishlist_requests"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wishlist-dealer_wishlist_requests"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wishlist-dealer_wishlist_requests" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wishlist-dealer_wishlist_requests"></code></pre>
</div>
<form id="form-POSTapi-customer-wishlist-dealer_wishlist_requests" data-method="POST" data-path="api/customer/wishlist/dealer_wishlist_requests" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wishlist-dealer_wishlist_requests', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wishlist-dealer_wishlist_requests" onclick="tryItOut('POSTapi-customer-wishlist-dealer_wishlist_requests');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wishlist-dealer_wishlist_requests" onclick="cancelTryOut('POSTapi-customer-wishlist-dealer_wishlist_requests');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wishlist-dealer_wishlist_requests" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wishlist/dealer_wishlist_requests</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wishlist-dealer_wishlist_requests" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wishlist-dealer_wishlist_requests" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>search</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="search" data-endpoint="POSTapi-customer-wishlist-dealer_wishlist_requests" data-component="body"  hidden>
<br>
optional Search Query.
</p>

</form>


## WishList: Dealer&#039;s Retailer Wishlist Request Details

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wishlist/dealer_wishlist_request_details" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"wish_list_id":1}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wishlist/dealer_wishlist_request_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "wish_list_id": 1
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Wish Return data found",
    "data": {
        "wish_return_id": "7",
        "retailer_name": "dada",
        "full_name": "Moriis ",
        "date": "24-01-2023",
        "time": "13:38 pm",
        "created_at": "2023-01-24T08:08:07.000000Z",
        "quantity": "1",
        "product_name": "test  hn",
        "product_image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
        "mrp": "500.00",
        "sale_price": "250",
        "contact_number": "1111112222",
        "state": "Gujrat",
        "city": "Nadiad",
        "postcode": "387002",
        "area": "area",
        "house_no": "16",
        "street": "pij",
        "landmark": "road"
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": {}
}
```
<div id="execution-results-POSTapi-customer-wishlist-dealer_wishlist_request_details" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wishlist-dealer_wishlist_request_details"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wishlist-dealer_wishlist_request_details"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wishlist-dealer_wishlist_request_details" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wishlist-dealer_wishlist_request_details"></code></pre>
</div>
<form id="form-POSTapi-customer-wishlist-dealer_wishlist_request_details" data-method="POST" data-path="api/customer/wishlist/dealer_wishlist_request_details" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wishlist-dealer_wishlist_request_details', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wishlist-dealer_wishlist_request_details" onclick="tryItOut('POSTapi-customer-wishlist-dealer_wishlist_request_details');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wishlist-dealer_wishlist_request_details" onclick="cancelTryOut('POSTapi-customer-wishlist-dealer_wishlist_request_details');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wishlist-dealer_wishlist_request_details" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wishlist/dealer_wishlist_request_details</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wishlist-dealer_wishlist_request_details" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wishlist-dealer_wishlist_request_details" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>wish_list_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="wish_list_id" data-endpoint="POSTapi-customer-wishlist-dealer_wishlist_request_details" data-component="body" required  hidden>
<br>

</p>

</form>


## Wish Return: All Wish Return List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wish_return/all_wish_return_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"language":"hindi","category_id":"3"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wish_return/all_wish_return_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "language": "hindi",
    "category_id": "3"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Wish Return data found",
    "data": [
        {
            "book_id": "5",
            "name": "gfagfagfag",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/avengers-death-infinity-war_16735055604656.jpg",
            "sale_price": "200.89",
            "mrp": "1111.00"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wish_return\/all_wish_return_list?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wish_return\/all_wish_return_list?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-POSTapi-customer-wish_return-all_wish_return_list" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wish_return-all_wish_return_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wish_return-all_wish_return_list"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wish_return-all_wish_return_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wish_return-all_wish_return_list"></code></pre>
</div>
<form id="form-POSTapi-customer-wish_return-all_wish_return_list" data-method="POST" data-path="api/customer/wish_return/all_wish_return_list" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wish_return-all_wish_return_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wish_return-all_wish_return_list" onclick="tryItOut('POSTapi-customer-wish_return-all_wish_return_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wish_return-all_wish_return_list" onclick="cancelTryOut('POSTapi-customer-wish_return-all_wish_return_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wish_return-all_wish_return_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wish_return/all_wish_return_list</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wish_return-all_wish_return_list" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wish_return-all_wish_return_list" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>language</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="language" data-endpoint="POSTapi-customer-wish_return-all_wish_return_list" data-component="body" required  hidden>
<br>
Language (hindi,english).
</p>
<p>
<b><code>category_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="category_id" data-endpoint="POSTapi-customer-wish_return-all_wish_return_list" data-component="body" required  hidden>
<br>
Category_id (category_id or subcategory_id of any level).
</p>

</form>


## Wish Return: My Wish Return List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/wish_return/my_wish_return_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/wish_return/my_wish_return_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Wish Return data found",
    "data": [
        {
            "wish_return_id": "7",
            "book_id": "4",
            "name": "test  hn",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
            "sale_price": "295",
            "quantity": "1",
            "description": "asfafafaa fffffffffffffffff",
            "dealer_id": "",
            "dealer_name": ""
        },
        {
            "wish_return_id": "1",
            "book_id": "4",
            "name": "test  hn",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
            "sale_price": "295",
            "quantity": "1",
            "description": "",
            "dealer_id": "8",
            "dealer_name": "dsdsdsd"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wish_return\/my_wish_return_list?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wish_return\/my_wish_return_list?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 2
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-GETapi-customer-wish_return-my_wish_return_list" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-wish_return-my_wish_return_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-wish_return-my_wish_return_list"></code></pre>
</div>
<div id="execution-error-GETapi-customer-wish_return-my_wish_return_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-wish_return-my_wish_return_list"></code></pre>
</div>
<form id="form-GETapi-customer-wish_return-my_wish_return_list" data-method="GET" data-path="api/customer/wish_return/my_wish_return_list" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-wish_return-my_wish_return_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-wish_return-my_wish_return_list" onclick="tryItOut('GETapi-customer-wish_return-my_wish_return_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-wish_return-my_wish_return_list" onclick="cancelTryOut('GETapi-customer-wish_return-my_wish_return_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-wish_return-my_wish_return_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/wish_return/my_wish_return_list</code></b>
</p>
<p>
<label id="auth-GETapi-customer-wish_return-my_wish_return_list" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-wish_return-my_wish_return_list" data-component="header"></label>
</p>
</form>


## Wish Return: Add to Wish Return

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wish_return/add_to_wish_return" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"book_id":"1","dealer_id":"3","description":"lorem ipsum","quantity":"3"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wish_return/add_to_wish_return"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "book_id": "1",
    "dealer_id": "3",
    "description": "lorem ipsum",
    "quantity": "3"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "The item is added into the wish return list.",
}
```
<div id="execution-results-POSTapi-customer-wish_return-add_to_wish_return" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wish_return-add_to_wish_return"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wish_return-add_to_wish_return"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wish_return-add_to_wish_return" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wish_return-add_to_wish_return"></code></pre>
</div>
<form id="form-POSTapi-customer-wish_return-add_to_wish_return" data-method="POST" data-path="api/customer/wish_return/add_to_wish_return" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wish_return-add_to_wish_return', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wish_return-add_to_wish_return" onclick="tryItOut('POSTapi-customer-wish_return-add_to_wish_return');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wish_return-add_to_wish_return" onclick="cancelTryOut('POSTapi-customer-wish_return-add_to_wish_return');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wish_return-add_to_wish_return" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wish_return/add_to_wish_return</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wish_return-add_to_wish_return" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wish_return-add_to_wish_return" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>book_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="book_id" data-endpoint="POSTapi-customer-wish_return-add_to_wish_return" data-component="body" required  hidden>
<br>
Book Id .
</p>
<p>
<b><code>dealer_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="dealer_id" data-endpoint="POSTapi-customer-wish_return-add_to_wish_return" data-component="body"  hidden>
<br>
optional from WishList: User's Dealer List.
</p>
<p>
<b><code>description</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="description" data-endpoint="POSTapi-customer-wish_return-add_to_wish_return" data-component="body"  hidden>
<br>
optional Max:500 Description.
</p>
<p>
<b><code>quantity</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="quantity" data-endpoint="POSTapi-customer-wish_return-add_to_wish_return" data-component="body" required  hidden>
<br>
Quantity.
</p>

</form>


## Wish Return: Edit Wish Return Item

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wish_return/edit_wish_return_item" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"wish_return_id":1,"quantity":2,"description":"lorem ipsum"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wish_return/edit_wish_return_item"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "wish_return_id": 1,
    "quantity": 2,
    "description": "lorem ipsum"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "The wish return item is updated.",
}
```
<div id="execution-results-POSTapi-customer-wish_return-edit_wish_return_item" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wish_return-edit_wish_return_item"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wish_return-edit_wish_return_item"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wish_return-edit_wish_return_item" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wish_return-edit_wish_return_item"></code></pre>
</div>
<form id="form-POSTapi-customer-wish_return-edit_wish_return_item" data-method="POST" data-path="api/customer/wish_return/edit_wish_return_item" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wish_return-edit_wish_return_item', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wish_return-edit_wish_return_item" onclick="tryItOut('POSTapi-customer-wish_return-edit_wish_return_item');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wish_return-edit_wish_return_item" onclick="cancelTryOut('POSTapi-customer-wish_return-edit_wish_return_item');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wish_return-edit_wish_return_item" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wish_return/edit_wish_return_item</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wish_return-edit_wish_return_item" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wish_return-edit_wish_return_item" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>wish_return_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="wish_return_id" data-endpoint="POSTapi-customer-wish_return-edit_wish_return_item" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>quantity</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="quantity" data-endpoint="POSTapi-customer-wish_return-edit_wish_return_item" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>description</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="description" data-endpoint="POSTapi-customer-wish_return-edit_wish_return_item" data-component="body" required  hidden>
<br>
Description
</p>

</form>


## Wish Return: Remove From Wish Return List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wish_return/remove_from_wishlist" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"wish_return_id":1}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wish_return/remove_from_wishlist"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "wish_return_id": 1
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "TThe wish return item is deleted.",
}
```
<div id="execution-results-POSTapi-customer-wish_return-remove_from_wishlist" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wish_return-remove_from_wishlist"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wish_return-remove_from_wishlist"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wish_return-remove_from_wishlist" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wish_return-remove_from_wishlist"></code></pre>
</div>
<form id="form-POSTapi-customer-wish_return-remove_from_wishlist" data-method="POST" data-path="api/customer/wish_return/remove_from_wishlist" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wish_return-remove_from_wishlist', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wish_return-remove_from_wishlist" onclick="tryItOut('POSTapi-customer-wish_return-remove_from_wishlist');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wish_return-remove_from_wishlist" onclick="cancelTryOut('POSTapi-customer-wish_return-remove_from_wishlist');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wish_return-remove_from_wishlist" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wish_return/remove_from_wishlist</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wish_return-remove_from_wishlist" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wish_return-remove_from_wishlist" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>wish_return_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="wish_return_id" data-endpoint="POSTapi-customer-wish_return-remove_from_wishlist" data-component="body" required  hidden>
<br>

</p>

</form>


## Wish Return: Wish Return Details

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wish_return/wish_return_details" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"wish_return_id":1}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wish_return/wish_return_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "wish_return_id": 1
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Wish Return data found",
    "data": {
        "wish_return_id": "7",
        "book_id": "4",
        "name": "test  hn",
        "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
        "sale_price": "295",
        "quantity": "1",
        "description": "asfafafaa fffffffffffffffff",
        "dealer_id": "",
        "dealer_name": ""
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": {}
}
```
<div id="execution-results-POSTapi-customer-wish_return-wish_return_details" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wish_return-wish_return_details"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wish_return-wish_return_details"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wish_return-wish_return_details" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wish_return-wish_return_details"></code></pre>
</div>
<form id="form-POSTapi-customer-wish_return-wish_return_details" data-method="POST" data-path="api/customer/wish_return/wish_return_details" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wish_return-wish_return_details', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wish_return-wish_return_details" onclick="tryItOut('POSTapi-customer-wish_return-wish_return_details');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wish_return-wish_return_details" onclick="cancelTryOut('POSTapi-customer-wish_return-wish_return_details');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wish_return-wish_return_details" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wish_return/wish_return_details</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wish_return-wish_return_details" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wish_return-wish_return_details" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>wish_return_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="wish_return_id" data-endpoint="POSTapi-customer-wish_return-wish_return_details" data-component="body" required  hidden>
<br>

</p>

</form>


## Wish Return: Dealer&#039;s Retailer Wish Return Request

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wish_return/dealer_wish_return_requests" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"search":"lorem"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wish_return/dealer_wish_return_requests"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "search": "lorem"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Wish List data found",
    "data": [
        {
            "wish_list_request_id": "1",
            "retailer_name": "dada",
            "product_name": "test  hn",
            "date": "24-01-2023",
            "time": "13:18 pm",
            "created_at": "2023-01-24T07:48:39.000000Z",
            "quantity": "1"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wishlist\/dealer_wishlist_requests?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/wishlist\/dealer_wishlist_requests?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-POSTapi-customer-wish_return-dealer_wish_return_requests" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wish_return-dealer_wish_return_requests"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wish_return-dealer_wish_return_requests"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wish_return-dealer_wish_return_requests" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wish_return-dealer_wish_return_requests"></code></pre>
</div>
<form id="form-POSTapi-customer-wish_return-dealer_wish_return_requests" data-method="POST" data-path="api/customer/wish_return/dealer_wish_return_requests" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wish_return-dealer_wish_return_requests', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wish_return-dealer_wish_return_requests" onclick="tryItOut('POSTapi-customer-wish_return-dealer_wish_return_requests');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wish_return-dealer_wish_return_requests" onclick="cancelTryOut('POSTapi-customer-wish_return-dealer_wish_return_requests');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wish_return-dealer_wish_return_requests" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wish_return/dealer_wish_return_requests</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wish_return-dealer_wish_return_requests" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wish_return-dealer_wish_return_requests" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>search</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="search" data-endpoint="POSTapi-customer-wish_return-dealer_wish_return_requests" data-component="body"  hidden>
<br>
optional Search Query.
</p>

</form>


## Wish Return: Dealer&#039;s Retailer Wish Return Request Details

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/wish_return/dealer_wishlist_request_details" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"wish_return_id":1}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/wish_return/dealer_wishlist_request_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "wish_return_id": 1
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Wish Return data found",
    "data": {
        "wish_return_id": "7",
        "retailer_name": "dada",
        "full_name": "Moriis ",
        "date": "24-01-2023",
        "time": "13:38 pm",
        "created_at": "2023-01-24T08:08:07.000000Z",
        "quantity": "1",
        "product_name": "test  hn",
        "product_image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
        "mrp": "500.00",
        "sale_price": "250",
        "contact_number": "1111112222",
        "state": "Gujrat",
        "city": "Nadiad",
        "postcode": "387002",
        "area": "area",
        "house_no": "16",
        "street": "pij",
        "landmark": "road"
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": {}
}
```
<div id="execution-results-POSTapi-customer-wish_return-dealer_wishlist_request_details" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-wish_return-dealer_wishlist_request_details"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-wish_return-dealer_wishlist_request_details"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-wish_return-dealer_wishlist_request_details" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-wish_return-dealer_wishlist_request_details"></code></pre>
</div>
<form id="form-POSTapi-customer-wish_return-dealer_wishlist_request_details" data-method="POST" data-path="api/customer/wish_return/dealer_wishlist_request_details" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-wish_return-dealer_wishlist_request_details', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-wish_return-dealer_wishlist_request_details" onclick="tryItOut('POSTapi-customer-wish_return-dealer_wishlist_request_details');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-wish_return-dealer_wishlist_request_details" onclick="cancelTryOut('POSTapi-customer-wish_return-dealer_wishlist_request_details');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-wish_return-dealer_wishlist_request_details" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/wish_return/dealer_wishlist_request_details</code></b>
</p>
<p>
<label id="auth-POSTapi-customer-wish_return-dealer_wishlist_request_details" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-customer-wish_return-dealer_wishlist_request_details" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>wish_return_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="wish_return_id" data-endpoint="POSTapi-customer-wish_return-dealer_wishlist_request_details" data-component="body" required  hidden>
<br>

</p>

</form>


## Suggestion: Suggestion Book list




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/suggestion_book_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"language":"hindi","category_id":"3"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/suggestion_book_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "language": "hindi",
    "category_id": "3"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Books available",
    "data": [
        {
            "book_id": "21",
            "name": "Harry Potter",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740395441227.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "20",
            "name": "the Bible",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740394854126.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "19",
            "name": "CATCH-22",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740393712254.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "18",
            "name": "THE SOUND AND THE FURY",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740392737976.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "17",
            "name": "BRAVE NEW WORLD",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740391962670.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "16",
            "name": "Art of living",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740390975437.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "15",
            "name": "Arthashastra",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740389989247.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "14",
            "name": "Corporate-Chanakya",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740389076333.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "13",
            "name": "Half girl friend",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740388208663.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "12",
            "name": "Beloved",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740387401019.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "11",
            "name": "Don Quixote",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740375109461.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "10",
            "name": "The Great Gatsby",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740374312465.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "9",
            "name": "To Kill a Mockingbird",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740373723833.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "8",
            "name": "atomic-habits",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740372599640.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        },
        {
            "book_id": "7",
            "name": "Believe-Yourself",
            "sale_price": "",
            "mrp": "500.00",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/uploads\/media\/book_16740371632382.png",
            "quantity": "1",
            "added_to_cart": "0",
            "cart_item_id": ""
        }
    ],
    "links": {
        "first": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/api\/customer\/suggestion_book_list?page=1",
        "last": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/api\/customer\/suggestion_book_list?page=2",
        "prev": "",
        "next": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc-bulk-order-web\/public\/api\/customer\/suggestion_book_list?page=2"
    },
    "meta": {
        "current_page": 1,
        "last_page": 2,
        "per_page": 15,
        "total": 18
    }
}
```
<div id="execution-results-POSTapi-customer-suggestion_book_list" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-suggestion_book_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-suggestion_book_list"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-suggestion_book_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-suggestion_book_list"></code></pre>
</div>
<form id="form-POSTapi-customer-suggestion_book_list" data-method="POST" data-path="api/customer/suggestion_book_list" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-suggestion_book_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-suggestion_book_list" onclick="tryItOut('POSTapi-customer-suggestion_book_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-suggestion_book_list" onclick="cancelTryOut('POSTapi-customer-suggestion_book_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-suggestion_book_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/suggestion_book_list</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>language</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="language" data-endpoint="POSTapi-customer-suggestion_book_list" data-component="body" required  hidden>
<br>
Language (hindi,english).
</p>
<p>
<b><code>category_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="category_id" data-endpoint="POSTapi-customer-suggestion_book_list" data-component="body" required  hidden>
<br>
Category_id (category_id or subcategory_id of any level).
</p>

</form>


## Suggestion:Ssgc Suggestion add




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/add_ssgc_suggestion" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"product_id":"3","description":"abc","mobile_number":"8787878787","email":"abc_@gamil.com"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/add_ssgc_suggestion"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "product_id": "3",
    "description": "abc",
    "mobile_number": "8787878787",
    "email": "abc_@gamil.com"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Ssgc Suggestion Added"
}
```
<div id="execution-results-POSTapi-customer-add_ssgc_suggestion" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-add_ssgc_suggestion"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-add_ssgc_suggestion"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-add_ssgc_suggestion" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-add_ssgc_suggestion"></code></pre>
</div>
<form id="form-POSTapi-customer-add_ssgc_suggestion" data-method="POST" data-path="api/customer/add_ssgc_suggestion" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-add_ssgc_suggestion', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-add_ssgc_suggestion" onclick="tryItOut('POSTapi-customer-add_ssgc_suggestion');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-add_ssgc_suggestion" onclick="cancelTryOut('POSTapi-customer-add_ssgc_suggestion');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-add_ssgc_suggestion" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/add_ssgc_suggestion</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>product_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="product_id" data-endpoint="POSTapi-customer-add_ssgc_suggestion" data-component="body" required  hidden>
<br>
Product_id.
</p>
<p>
<b><code>description</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="description" data-endpoint="POSTapi-customer-add_ssgc_suggestion" data-component="body" required  hidden>
<br>
Description
</p>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-add_ssgc_suggestion" data-component="body" required  hidden>
<br>
Mobile Number
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-customer-add_ssgc_suggestion" data-component="body" required  hidden>
<br>
Email
</p>

</form>


## Suggestion:Wish Suggestion add




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/add_wish_suggestion" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"book_name":"abc","subject":"sparsh","description":"abc","mobile_number":"8787878787","email":"abc_@gamil.co","images":"img\/pdf","pdf":"pdf"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/add_wish_suggestion"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "book_name": "abc",
    "subject": "sparsh",
    "description": "abc",
    "mobile_number": "8787878787",
    "email": "abc_@gamil.co",
    "images": "img\/pdf",
    "pdf": "pdf"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Wish Suggestion Added"
}
```
<div id="execution-results-POSTapi-customer-add_wish_suggestion" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-add_wish_suggestion"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-add_wish_suggestion"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-add_wish_suggestion" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-add_wish_suggestion"></code></pre>
</div>
<form id="form-POSTapi-customer-add_wish_suggestion" data-method="POST" data-path="api/customer/add_wish_suggestion" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-add_wish_suggestion', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-add_wish_suggestion" onclick="tryItOut('POSTapi-customer-add_wish_suggestion');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-add_wish_suggestion" onclick="cancelTryOut('POSTapi-customer-add_wish_suggestion');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-add_wish_suggestion" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/add_wish_suggestion</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>book_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="book_name" data-endpoint="POSTapi-customer-add_wish_suggestion" data-component="body" required  hidden>
<br>
Book Name
</p>
<p>
<b><code>subject</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="subject" data-endpoint="POSTapi-customer-add_wish_suggestion" data-component="body" required  hidden>
<br>
Subject .
</p>
<p>
<b><code>description</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="description" data-endpoint="POSTapi-customer-add_wish_suggestion" data-component="body" required  hidden>
<br>
Description
</p>
<p>
<b><code>mobile_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="mobile_number" data-endpoint="POSTapi-customer-add_wish_suggestion" data-component="body" required  hidden>
<br>
Mobile Number
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-customer-add_wish_suggestion" data-component="body" required  hidden>
<br>
Email
</p>
<p>
<b><code>images</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="images" data-endpoint="POSTapi-customer-add_wish_suggestion" data-component="body"  hidden>
<br>
Images
</p>
<p>
<b><code>pdf</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="pdf" data-endpoint="POSTapi-customer-add_wish_suggestion" data-component="body"  hidden>
<br>
Pdf
</p>

</form>


## Home: Make My Return

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/home/make_my_return" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/home/make_my_return"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": {
        "returnable_books": [
            {
                "order_item_id": "3",
                "book_id": "4",
                "order_id": "2",
                "name": "test  hn",
                "sale_price": "245.00",
                "mrp": "500.00",
                "quantity": "1",
                "max_returnable_qty": "0",
                "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/favicon_16735053078189.png",
                "added_to_cart": "0"
            }
        ],
        "previous_orders": [
            {
                "order_return_id": "9",
                "book_id": "5",
                "name": "gfagfagfag",
                "sale_price": "301.89",
                "mrp": "1111.00",
                "quantity": "2",
                "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/avengers-death-infinity-war_16735055604656.jpg"
            }
        ]
    }
}
```
> Example response (200, error):

```json
{
    "success": "0",
    "status": "201",
    "message": "Something went wrong.",
    "data": []
}
```
<div id="execution-results-GETapi-customer-home-make_my_return" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-home-make_my_return"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-home-make_my_return"></code></pre>
</div>
<div id="execution-error-GETapi-customer-home-make_my_return" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-home-make_my_return"></code></pre>
</div>
<form id="form-GETapi-customer-home-make_my_return" data-method="GET" data-path="api/customer/home/make_my_return" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-home-make_my_return', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-home-make_my_return" onclick="tryItOut('GETapi-customer-home-make_my_return');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-home-make_my_return" onclick="cancelTryOut('GETapi-customer-home-make_my_return');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-home-make_my_return" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/home/make_my_return</code></b>
</p>
<p>
<label id="auth-GETapi-customer-home-make_my_return" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-home-make_my_return" data-component="header"></label>
</p>
</form>


## Coupon: Coupon list




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/coupon_list" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"category_id":"3"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/coupon_list"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "category_id": "3"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Coupon available",
    "data": [
        {
            "sub_coupon_id": "2",
            "name": "Course 5\/9 Multiple coupon",
            "sale_price": "100.00",
            "mrp": "200.00",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/canada-flag-png-4604_(1)_16746497122843.png",
            "type": "courses",
            "expiry_date": "2023-09-29 19:00:00"
        },
        {
            "sub_coupon_id": "1",
            "name": "Coupon Multi 14",
            "sale_price": "50.00",
            "mrp": "100.00",
            "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/canada-flag-png-4604_-_Copy_16746485149778.png",
            "type": "books",
            "expiry_date": "2024-10-31 15:30:00"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/coupon_list?page=1",
        "last": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/api\/customer\/coupon_list?page=1",
        "prev": "",
        "next": ""
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 2
    }
}
```
<div id="execution-results-POSTapi-customer-coupon_list" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-coupon_list"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-coupon_list"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-coupon_list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-coupon_list"></code></pre>
</div>
<form id="form-POSTapi-customer-coupon_list" data-method="POST" data-path="api/customer/coupon_list" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-coupon_list', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-coupon_list" onclick="tryItOut('POSTapi-customer-coupon_list');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-coupon_list" onclick="cancelTryOut('POSTapi-customer-coupon_list');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-coupon_list" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/coupon_list</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>category_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="category_id" data-endpoint="POSTapi-customer-coupon_list" data-component="body" required  hidden>
<br>
Category_id (category_id or subcategory_id of any level).
</p>

</form>


## Coupon: Coupon Detail




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/coupon_detail" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"sub_coupon_id":"3"}'

```

```javascript
const url = new URL(
    "http://localhost/api/customer/coupon_detail"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "sub_coupon_id": "3"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Coupon Details",
    "data": {
        "sub_coupon_id": "1",
        "name": "Railway Discount",
        "sale_price": "10.00",
        "mrp": "20.00",
        "image": "http:\/\/localhost\/ssgc-bulk-order-web\/public\/uploads\/media\/image_2021_08_11T13_34_48_563Z_1674641468276.png",
        "type": "e_books",
        "expiry_date": "2023-09-29 13:15:00",
        "description": "meet mehta"
    }
}
```
<div id="execution-results-POSTapi-customer-coupon_detail" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-coupon_detail"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-coupon_detail"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-coupon_detail" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-coupon_detail"></code></pre>
</div>
<form id="form-POSTapi-customer-coupon_detail" data-method="POST" data-path="api/customer/coupon_detail" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-coupon_detail', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-coupon_detail" onclick="tryItOut('POSTapi-customer-coupon_detail');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-coupon_detail" onclick="cancelTryOut('POSTapi-customer-coupon_detail');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-coupon_detail" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/coupon_detail</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>sub_coupon_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="sub_coupon_id" data-endpoint="POSTapi-customer-coupon_detail" data-component="body" required  hidden>
<br>
Sub Coupon Id .
</p>

</form>


## Nested Categories: List

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/nested_categories/iure" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/nested_categories/iure"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "category_id": "1",
            "category_name": "Master 1",
            "sub_cat": [
                {
                    "category_id": "2",
                    "category_name": "L2 11",
                    "sub_cat": [
                        {
                            "category_id": "4",
                            "category_name": "L3 111",
                            "sub_cat": [
                                {
                                    "category_id": "5",
                                    "category_name": "L4 1111",
                                    "sub_cat": [
                                        {
                                            "category_id": "7",
                                            "category_name": "L5 11111",
                                            "sub_cat": [
                                                {
                                                    "category_id": "8",
                                                    "category_name": "L6 111111",
                                                    "sub_cat": [
                                                        {
                                                            "category_id": "9",
                                                            "category_name": "L7 1111111",
                                                            "sub_cat": []
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                {
                    "category_id": "3",
                    "category_name": "L2 12",
                    "sub_cat": []
                },
                {
                    "category_id": "33",
                    "category_name": "NewCategory",
                    "sub_cat": []
                },
                {
                    "category_id": "34",
                    "category_name": "Abc",
                    "sub_cat": []
                }
            ]
        },
        {
            "category_id": "6",
            "category_name": "Master 2",
            "sub_cat": []
        }
    ]
}
```
<div id="execution-results-GETapi-customer-nested_categories--business_category_id--" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-nested_categories--business_category_id--"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-nested_categories--business_category_id--"></code></pre>
</div>
<div id="execution-error-GETapi-customer-nested_categories--business_category_id--" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-nested_categories--business_category_id--"></code></pre>
</div>
<form id="form-GETapi-customer-nested_categories--business_category_id--" data-method="GET" data-path="api/customer/nested_categories/{business_category_id?}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-nested_categories--business_category_id--', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-nested_categories--business_category_id--" onclick="tryItOut('GETapi-customer-nested_categories--business_category_id--');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-nested_categories--business_category_id--" onclick="cancelTryOut('GETapi-customer-nested_categories--business_category_id--');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-nested_categories--business_category_id--" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/nested_categories/{business_category_id?}</code></b>
</p>
<p>
<label id="auth-GETapi-customer-nested_categories--business_category_id--" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-customer-nested_categories--business_category_id--" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>business_category_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="business_category_id" data-endpoint="GETapi-customer-nested_categories--business_category_id--" data-component="url"  hidden>
<br>

</p>
</form>


## Home: Business Categories




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/business_categories" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/business_categories"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "id": "1",
            "name": "Books",
            "type": "books",
            "url": "",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc\/public\/uploads\/media\/0bc1554ec2c118bff175ab17e332c6e0.png"
        },
        {
            "id": "2",
            "name": "E- Books",
            "type": "e_books",
            "url": "",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc\/public\/uploads\/media\/84a4a640eff66b428b089c933a0edd05.png"
        }
    ]
}
```
<div id="execution-results-GETapi-customer-business_categories" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-business_categories"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-business_categories"></code></pre>
</div>
<div id="execution-error-GETapi-customer-business_categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-business_categories"></code></pre>
</div>
<form id="form-GETapi-customer-business_categories" data-method="GET" data-path="api/customer/business_categories" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-business_categories', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-business_categories" onclick="tryItOut('GETapi-customer-business_categories');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-business_categories" onclick="cancelTryOut('GETapi-customer-business_categories');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-business_categories" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/business_categories</code></b>
</p>
</form>


## Master: Dealers List




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/dealers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/dealers"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "id": "1",
            "name": "Books",
            "type": "books",
            "url": "",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc\/public\/uploads\/media\/0bc1554ec2c118bff175ab17e332c6e0.png"
        },
        {
            "id": "2",
            "name": "E- Books",
            "type": "e_books",
            "url": "",
            "image": "http:\/\/cloud1.kodyinfotech.com:7000\/ssgc\/public\/uploads\/media\/84a4a640eff66b428b089c933a0edd05.png"
        }
    ]
}
```
<div id="execution-results-GETapi-customer-dealers" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-dealers"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-dealers"></code></pre>
</div>
<div id="execution-error-GETapi-customer-dealers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-dealers"></code></pre>
</div>
<form id="form-GETapi-customer-dealers" data-method="GET" data-path="api/customer/dealers" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-dealers', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-dealers" onclick="tryItOut('GETapi-customer-dealers');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-dealers" onclick="cancelTryOut('GETapi-customer-dealers');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-dealers" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/dealers</code></b>
</p>
</form>


## Home: Contact Us Info




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/contact_us_info" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/contact_us_info"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

{
"success": "1",
"status": "200",
"message": "Data Found Successfully",
"data": {
"contact_email": "1.0.0",
"contact_mobile": "soft",
}
}
```
<div id="execution-results-GETapi-customer-contact_us_info" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-contact_us_info"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-contact_us_info"></code></pre>
</div>
<div id="execution-error-GETapi-customer-contact_us_info" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-contact_us_info"></code></pre>
</div>
<form id="form-GETapi-customer-contact_us_info" data-method="GET" data-path="api/customer/contact_us_info" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-contact_us_info', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-contact_us_info" onclick="tryItOut('GETapi-customer-contact_us_info');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-contact_us_info" onclick="cancelTryOut('GETapi-customer-contact_us_info');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-contact_us_info" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/contact_us_info</code></b>
</p>
</form>


## For Backend Use




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/customer/bulk_coupons" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/bulk_coupons"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": "1",
    "status": "200",
    "message": "Coupon Added Successfully"
}
```
<div id="execution-results-GETapi-customer-bulk_coupons" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-customer-bulk_coupons"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-bulk_coupons"></code></pre>
</div>
<div id="execution-error-GETapi-customer-bulk_coupons" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-bulk_coupons"></code></pre>
</div>
<form id="form-GETapi-customer-bulk_coupons" data-method="GET" data-path="api/customer/bulk_coupons" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-bulk_coupons', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-customer-bulk_coupons" onclick="tryItOut('GETapi-customer-bulk_coupons');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-customer-bulk_coupons" onclick="cancelTryOut('GETapi-customer-bulk_coupons');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-customer-bulk_coupons" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/customer/bulk_coupons</code></b>
</p>
</form>


## For Backend Use Only




> Example request:

```bash
curl -X POST \
    "http://localhost/api/customer/update_coupon" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://localhost/api/customer/update_coupon"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


<div id="execution-results-POSTapi-customer-update_coupon" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-customer-update_coupon"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-update_coupon"></code></pre>
</div>
<div id="execution-error-POSTapi-customer-update_coupon" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-update_coupon"></code></pre>
</div>
<form id="form-POSTapi-customer-update_coupon" data-method="POST" data-path="api/customer/update_coupon" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-update_coupon', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-customer-update_coupon" onclick="tryItOut('POSTapi-customer-update_coupon');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-customer-update_coupon" onclick="cancelTryOut('POSTapi-customer-update_coupon');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-customer-update_coupon" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/customer/update_coupon</code></b>
</p>
</form>



