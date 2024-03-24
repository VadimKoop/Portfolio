import pandas as pd

# Loading data from Excel file.
file_path = r"PUT PATH HERE + FORMAT" #Put correct path to file.
df = pd.read_excel(file_path)

# Converting date strings to datetime format.
df['Date'] = pd.to_datetime(df['Date']).dt.tz_localize(None)  # Removing time zone information.

# We convert the date to the following format "year, month, day".
df['Date'] = pd.to_datetime(df['Date']).dt.strftime('%Y-%m-%d')

# Removing 'Quantity' and 'Price per share' columns.
df.drop(columns=['Quantity', 'Price per share'], inplace=True)

# Filtering rows named "DIVIDEND".
dividend_transactions = df[df['Type'].str.contains("BUY|SELL", case=False)]

# Saving filtered data to a new Excel file.
output_file_path = r"PUT PATH HERE + FORMAT" #Put correct path to file.
dividend_transactions.to_excel(output_file_path, index=False)

print("Rows named 'DIVIDEND' were saved in a new Excel file:", output_file_path)
