uses
  IdHTTP, idMultipartFormData, json;

procedure TfrmMain.btnOCRStartClick(Sender: TObject);
var
  IdHTTP1: TIdHTTP;
  MPData: TIdMultiPartFormDataStream;
  JsonObject: TJSONObject;
  JSonValue: TJSONValue;
  sResponse: string;
  sValue: string;
begin
  try
    IdHTTP1 := TIdHTTP.Create(nil);
    IdHTTP1.Request.ContentType := 'application/x-www-form-urlencoded';

    MPData := TIdMultiPartFormDataStream.Create;
    MPData.AddFile('file', 'C:\Pictures\Picture.jpg', 'image/jpg');
    MPData.AddFormField('apikey', '012345678901234');
    MPData.AddFormField('language', 'eng');
    MPData.AddFormField('isOverlayRequired', 'False');

    sResponse := IdHTTP1.Post('https://api.ocr.space/parse/image', MPData);

    JsonObject := TJSONObject.Create;
    JsonValue := JsonObject.ParseJSONValue(sResponse);
    JsonValue:=(JsonValue as TJSONObject).Get('ParsedResults').JSONValue;

    if (JsonValue is TJSONArray) then
      sValue := ((JsonValue as TJSONArray).Items[0] as TJSonObject).Get('ParsedText').JSONValue.Value;

    Memo1.Lines.Add(sValue);
  finally
    IdHTTP1.Free;
    MPData.Free;
    JsonObject.Free;
  end;
end;