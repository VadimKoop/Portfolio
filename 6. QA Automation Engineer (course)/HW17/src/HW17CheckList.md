## 1. Make a checklist of functional checks of the UI of the tallinn delivery application in terms of login.

| № | Test name                                  | Test description                                                                                 | Expected result                                                                                                                                                                    | Status |
|---|--------------------------------------------|--------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|--------|
| 1 | Login landing page                         | Verify that users are directed to the login landing page when accessing the login functionality. | Access the application login page. Verify that the user is directed to the login landing page.                                                                                     | Passed |
| 2 | Login data entering                        | Confirm that users can enter their login credentials successfully.                               | Enter valid login credentials (username and password). Submit the login form. Verify that the login data is entered successfully.                                                  | Passed |
| 3 | Login wrong username and password entering | Validate the handling of incorrect login credentials.                                            | Enter an invalid username and password combination. Submit the login form. Verify that the appropriate error message is displayed.                                                 | Passed |
| 4 | Incorrect credentials                      | Ensure that users cannot log in with incorrect credentials.                                      | Enter a combination of incorrect username and password. Submit the login form. Verify that login is not successful and users are prompted to enter correct credentials.            | Passed |
| 5 | Succesfull login and password entering     | Confirm that users can successfully log in with valid credentials.                               | Enter valid login credentials (username and password). Submit the login form. Verify that users are successfully logged in and directed to the intended page after authentication. | Passed |

## 2. Make a list of web elements and their locators (xpath) required for implementation checks from point 1 in table form.

| Nr | Element name               | Xpath                            |
|----|----------------------------|----------------------------------|
| 1  | Username text field        | //*[@data-name="username-input"] |
| 2  | Password text field        | //*[@data-name="password-input"] |        
| 3  | ENG language switch button | //button[text()='EN']            |            
| 4  | RU language switch button  | //button[text()='RU']            |
| 5  | Sign in button   	         | //*[@data-name="signIn-button"]  |

## 3. Supplement the readme of the current project (with API autotests) with the results of points 1 and 2.

**Configuration**

1. Open the `src/test/resources/application.yaml` file.
2. Update with your credentials:

    ```yaml
      general:
      username: YOUR_API_KEY_HERE
    ```

**Usage**

```
mvn clean test  
mvn allure:serve 
```

To run a specific test class using Maven, execute the following command in the terminal:

```
mvn clean test -Dtest=TestClassName
```

## 4. For an html document, compose xpath

4.1 Select all <td> elements containing names (Name): -> //td[contains(., 'Name')]
4.2 Select all <tr> elements where the data-qa attribute begins with "amount-": -> //tr//td//p[starts-with(.,'Amount')]
4.3 Select all <tr> elements containing a <td> element with the text "John Doe": -> //tr//td//p[contains(., 'John Doe')]


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <title>Table Example</title>
  <meta name="Generator" content="Cocoa HTML Writer">
  <meta name="CocoaVersion" content="2113.6">
  <style type="text/css">
    p.p1 {margin: 0.0px 0.0px 0.0px 0.0px; text-align: center; font: 12.0px Times; color: #000000; -webkit-text-stroke: #000000}
    p.p2 {margin: 0.0px 0.0px 0.0px 0.0px; font: 12.0px Times; color: #000000; -webkit-text-stroke: #000000}
    p.p3 {margin: 0.0px 0.0px 0.0px 0.0px; font: 12.0px Times; color: #fb0007; -webkit-text-stroke: #fb0007; min-height: 14.0px}
    span.s1 {font-kerning: none}
    table.t1 {border-style: solid; border-width: 1.0px 1.0px 1.0px 1.0px; border-color: #6d6d6d #6d6d6d #6d6d6d #6d6d6d}
    td.td1 {width: 42.7px; margin: 0.5px 0.5px 0.5px 0.5px; border-style: solid; border-width: 1.0px 1.0px 1.0px 1.0px; border-color: #6d6d6d #6d6d6d #6d6d6d #6d6d6d; padding: 1.0px 1.0px 1.0px 1.0px}
    td.td2 {width: 42.0px; margin: 0.5px 0.5px 0.5px 0.5px; border-style: solid; border-width: 1.0px 1.0px 1.0px 1.0px; border-color: #6d6d6d #6d6d6d #6d6d6d #6d6d6d; padding: 1.0px 1.0px 1.0px 1.0px}
    td.td3 {width: 67.7px; margin: 0.5px 0.5px 0.5px 0.5px; border-style: solid; border-width: 1.0px 1.0px 1.0px 1.0px; border-color: #6d6d6d #6d6d6d #6d6d6d #6d6d6d; padding: 1.0px 1.0px 1.0px 1.0px}
  </style>
</head>
<body>
<table cellspacing="0" cellpadding="0" class="t1">
  <tbody>
    <tr>
      <td valign="middle" class="td1">
        <p class="p1"><span class="s1"><b>Number</b></span></p>
      </td>
      <td valign="middle" class="td2">
        <p class="p1"><span class="s1"><b>Amount</b></span></p>
      </td>
      <td valign="middle" class="td3">
        <p class="p1"><span class="s1"><b>Name</b></span></p>
      </td>
    </tr>
    <tr>
      <td valign="middle" class="td1">
        <p class="p2"><span class="s1">1</span></p>
      </td>
      <td valign="middle" class="td2">
        <p class="p2"><span class="s1">$100.00</span></p>
      </td>
      <td valign="middle" class="td3">
        <p class="p2"><span class="s1">John Doe</span></p>
      </td>
    </tr>
    <tr>
      <td valign="middle" class="td1">
        <p class="p2"><span class="s1">2</span></p>
      </td>
      <td valign="middle" class="td2">
        <p class="p2"><span class="s1">$50.00</span></p>
      </td>
      <td valign="middle" class="td3">
        <p class="p2"><span class="s1">Jane Smith</span></p>
      </td>
    </tr>
    <tr>
      <td valign="middle" class="td1">
        <p class="p2"><span class="s1">3</span></p>
      </td>
      <td valign="middle" class="td2">
        <p class="p2"><span class="s1">$75.00</span></p>
      </td>
      <td valign="middle" class="td3">
        <p class="p2"><span class="s1">Mike Johnson</span></p>
      </td>
    </tr>
    <tr>
      <td valign="middle" class="td1">
        <p class="p2"><span class="s1">4</span></p>
      </td>
      <td valign="middle" class="td2">
        <p class="p2"><span class="s1">$120.00</span></p>
      </td>
      <td valign="middle" class="td3">
        <p class="p2"><span class="s1">Susan Lee</span></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="p3"><span class="s1"></span><br></p>
</body>
</html>