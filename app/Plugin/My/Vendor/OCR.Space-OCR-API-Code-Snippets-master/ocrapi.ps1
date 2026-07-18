#POWERSHELL OCR API CALL - V2.0, May 30, 2020
#In this demo we send an image link to the OCR API and download the text result and the searchable PDF

#Enter your api key here
$apikey =  "helloworld"
$apiUrl = "https://api.ocr.space/parse/image" 

$pathlog = $PSScriptRoot + "\ocrlog.txt"


#Call API with CURL
curl.exe -X POST $apiurl -H "apikey:$apikey" -F "url=http://dl.a9t9.com/apitest/testimage.png" -F "language=eng" -F "isOverlayRequired=false" -F "iscreatesearchablepdf=true"| ConvertFrom-Json -OutVariable response

#Done, write OCR'ed text to log file
$text = $response.ParsedResults.ParsedText
Write-Host $text
$text  | Add-Content $pathlog


#If there is a searchable PDF, then download it
$filepath = $PSScriptRoot +"\testpdf.pdf "
$response = $response | select SearchablePDFURL
if ($response.SearchablePDFURL -ne $null){
    try {
         Invoke-WebRequest -Uri $response.SearchablePDFURL -OutFile $filepath
         $status = "Download OK. " + $response.OCRExitCode
        } catch {
         $status = "Error PDF Download: " + $_.Exception.Response + " URL="+$response.SearchablePDFURL
        }
}
else {$status= "No searchable PDF " + $response.OCRExitCode }


#Finally, log the status
$out = [String]$(Get-Date) +" "+$status
Write-Host $out
$out  | Add-Content $pathlog


