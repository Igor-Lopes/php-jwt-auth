php-jwt-manager
======
A class to decode, verify and generate tokens with the php-jwt library.

Quick Intro to JSON Web Tokens:
------

JSON Web Tokens are an easy way to authenticate an API. Unlikely in the traditional Server Session Authentication, the session data is stored only in the client side, reducing database queries, improving the backend performance and scalability.

### Some Advantages of JWT:

* JSON Web Tokens can be used in different languages: JWT libraries are available for several languages;
* Practical : JSON Web Tokens are easy to pass in HTTP headers or through URLs;
* Better Performance and Scalability: Since JSON Web Tokens are stateless, they reduce database queries and improve backend performance, as well, make it possible to have a distributed or clustered infrastructure that share the same authentication method.
* Mobile Friendly: The token storage is not limited to cookies, they can be stored in mobile databases;

Installation:
------

1. Install the php-jwt library:   
`composer require firebase/php-jwt`
2. Copy the contents of src to your selected subfolder.
3. Include `JWTAuth.php` in your file with `require` or `require_once`.

**Composer support will be available soon**

Usage:
------

### Instantiate JWTManager Class:  
`$jwt = new JWTManager();`

### Generate a token:  
In order to generate a token, we have to pass the extra data that will be in the token, for example:  
`$token = $jwt-> encodeToken("admin");`

### Decode and verify a token:  
`$data = $jwt-> decodeToken($jwt);`  

The method `decodeToken` will return a JSON:  

| Key       | Description         | Value  |
| ------------- |:-------------:| -----:|
| valid     | Defines whether the token is valid or not | Boolean |
| message      | Description of the token verification result       |   String |
| scope | Extra data in the token      |    String |
  
 Examples of the returned JSON:  
 **Success**:  
 
 `{"valid":true,"message":"Token is valid","scope":"admin"}`    
 
 **Error**:  
 
 `{"valid":false,"message":"Expired token"}`  
  
 Credits:
------
  
 [PHP JWT Library](https://github.com/firebase/php-jwt)  
 Copyright (c) 2011, Neuman Vong  
 All rights reserved.
 
Contributing:
------
 
 Issues, Pull requests and questions are very welcome. 
 
License:  
------
 
 This project is licensed under the [MIT License] (https://opensource.org/licenses/MIT) and the [PHP JWT Library](https://github.com/firebase/php-jwt) is licensed under [3-Clause BSD] (https://opensource.org/licenses/BSD-3-Clause).
