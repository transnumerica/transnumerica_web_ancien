Rem Example cURL calls fpr the OCR API at https://ocr.space/Parse/Image

Rem cURL main site: https://curl.haxx.se/download.html
Rem cURL for Windows: http://www.confusedbycode.com/curl/

Rem (1) Submitting the file via URL

curl https://api.ocr.space/Parse/Image -H "apikey:helloworld"  --data "isOverlayRequired=true&url=http://dl.a9t9.com/blog/ocr-online/screenshot.jpg&language=eng"
pause

Rem (2) Submitting the file via file upload - this expects a "ingredients.jpg" image in the same folder 

curl  -H "apikey:helloworld" --form "file=@ingredients.jpg" --form "language=eng" --form "isOverlayRequired=true" https://api.ocr.space/parse/image 
pause

