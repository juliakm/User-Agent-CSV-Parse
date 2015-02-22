# User-Agent-CSV-Parse
Example parsing user agent in a CSV row and outputting result to CSV

Code uses the excellent UA parser library to do the following:
1. Open and read a CSV file row by row. 
2. On a row-by-row basis, take the column with the user agent string stored and detect whether the user agent is mobile, desktop, tablet, or spider
3. Output the result to a different CSV column in the same row. 
