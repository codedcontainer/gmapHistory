# gmapHistory
Uses the Android Google tracking option and sends data to your personal database.
# How It Works
- Through a web browser go to your Google Location History page and save the pages cookies to a text document. Save this to your server
- Connect to Google Location History through CURL with the cookie document you saved earlier.
- Save the content as an XML document to your server ( in a data directory ). 
- Parse through the XML document with PHP and retrieve the date, time, and coordinates.
- Create a new database table for each day and add the previous data in separate rows. 
# Risks
- Not sure how often the cookie document needs to be updated, don't yet know of a workaround. 

