To add your attached Arar photo to the site:

1) Save the attached image file with the name: arar.jpg
2) Place the file into the project image folder:
   c:\Users\Maznh\Documents\GitHub\web-project\image\arar.jpg

PowerShell example (adjust the source path where you saved the attachment):
Copy-Item -Path "C:\path\to\downloaded\attachment.jpg" -Destination "c:\Users\Maznh\Documents\GitHub\web-project\image\arar.jpg"

After copying, refresh your `North.html` page in the browser. The page prefers `image/arar.jpg`; if it's not present it will display the placeholder `image/arar.svg`.

Notes:
- Use the filename exactly `arar.jpg` (lowercase) to match the HTML reference.
- If you want a different format (PNG), update the HTML source element and filename accordingly.