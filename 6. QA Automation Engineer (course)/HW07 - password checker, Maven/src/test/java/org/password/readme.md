| № |                             Test name                              |                                                 Test description | Expected result  | Status |
|:--|:------------------------------------------------------------------:|-----------------------------------------------------------------:|:----------------:|-------:|
| 1 |    shouldReturnTrueForComplexPasswordWithDigitsAndSpecialChars     |                 Checking password length > 8 and special symbol. | Boolean -> True  | Passed |
| 2 |               shouldReturnFalsePasswordInputIsEmpty                |                                     Checking password emptiness. | Boolean -> False | Passed |
| 3 | shouldReturnFalseForNotEnoughComplexPasswordWithMissingSpecialChar | Checking password length < 8 and special missing special symbol. | Boolean -> False | Passed |
| 4 |     shouldReturnFalseForNotEnoughComplexPasswordNotEnoughLong      |                                    Checking password length < 8. | Boolean -> False | Passed |
| 5 |   shouldReturnFalseForNotEnoughComplexPasswordWithMissingNumber    |              Checking password length < 8 and number is missing. | Boolean -> False | Passed |
| 6 |   shouldReturnTrueForComplexPasswordWithDigitsAndSpecialRusChars   |      Checking password length > 8 and russian letter and number. | Boolean -> True  | Passed |