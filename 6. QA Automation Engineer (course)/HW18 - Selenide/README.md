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