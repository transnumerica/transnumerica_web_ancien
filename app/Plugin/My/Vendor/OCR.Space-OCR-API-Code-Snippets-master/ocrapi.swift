func callOCRSpace() {
        // Create URL request
        let url = URL(string: "https://api.ocr.space/Parse/Image")
        var request: URLRequest? = nil
        if let url = url {
            request = URLRequest(url: url)
        }
        request?.httpMethod = "POST"
        let boundary = "randomString"
        request?.addValue("multipart/form-data; boundary=\(boundary)", forHTTPHeaderField: "Content-Type")
        
        let session = URLSession.shared
        
        // Image file and parameters
        let imageData = UIImage(named: "yourImage")?.jpegData(compressionQuality: 0.6)
        let parametersDictionary = ["apikey" : "yourAPIKey", "isOverlayRequired" : "True", "language" : "eng"]

        // Create multipart form body
        let data = createBody(withBoundary: boundary, parameters: parametersDictionary, imageData: imageData, filename: "yourImage.jpg")

        request?.httpBody = data

        // Start data session
        var task: URLSessionDataTask? = nil
        if let request = request {
            task = session.dataTask(with: request, completionHandler: { (data, response, error) in
                var result: [AnyHashable : Any]? = nil
                do {
                    if let data = data {
                        result = try JSONSerialization.jsonObject(with: data, options: []) as? [AnyHashable : Any]
                    }
                } catch let myError {
                    print(myError)
                }
                print(result!)
            })
        }
        task?.resume()
    }

    func createBody(withBoundary boundary: String?, parameters: [AnyHashable : Any]?, imageData data: Data?, filename: String?) -> Data? {
        var body = Data()
        if data != nil {
            if let data1 = "--\(boundary ?? "")\r\n".data(using: .utf8) {
                body.append(data1)
            }
            if let data1 = "Content-Disposition: form-data; name=\"\("file")\"; filename=\"\(filename ?? "")\"\r\n".data(using: .utf8) {
                body.append(data1)
            }
            if let data1 = "Content-Type: image/jpeg\r\n\r\n".data(using: .utf8) {
                body.append(data1)
            }
            if let data = data {
                body.append(data)
            }
            if let data1 = "\r\n".data(using: .utf8) {
                body.append(data1)
            }
        }

        for key in parameters!.keys {
            if let data1 = "--\(boundary ?? "")\r\n".data(using: .utf8) {
                body.append(data1)
            }
            if let data1 = "Content-Disposition: form-data; name=\"\(key)\"\r\n\r\n".data(using: .utf8) {
                body.append(data1)
            }
            if let parameter = parameters?[key], let data1 = "\(parameter)\r\n".data(using: .utf8) {
                body.append(data1)
            }
        }

        if let data1 = "--\(boundary ?? "")--\r\n".data(using: .utf8) {
            body.append(data1)
        }

        return body
    }
