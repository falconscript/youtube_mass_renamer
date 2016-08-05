# Mass YouTube Renamer
ExpressJS application using YouTube API and OCR API to generate YouTube titles
and descriptions from thumbnails for videos.  
Designed to automate renaming thousands of videos quickly.  

# Usage
Get an API key and username from http://www.ocrwebservice.com and place them in youtubestuff.js

To get up and running:
```bash
git clone https://github.com/falconscript/youtube_mass_renamer
cd youtube_mass_renamer
npm install
npm start # Start server
```

Once the server's up and running, open a Chrome tab, paste http://localhost:3000
in the address bar and hit enter. Follow the onscreen instructions from there.


# Caveats
The major limitations are speed of the API and image downloading. The current OCR
API also limits requests. Using multiple API keys/IPs would be able to get around that however.

# Credits
Made by just me for the public good  
http://c-cfalcon.rhcloud.com
