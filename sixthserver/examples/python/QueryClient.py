import urllib.parse
import urllib.request
import json

class User:
    def __init__(self, username, password, host):
        self.host = host
        self.auth = self.login(username, password)
        if self.auth == False:
            print("Error")
    
    def query(self, url, data):
        url = self.host + url
        headers = {"Authorization": self.auth}
        
        if data != None:
            data = urllib.parse.urlencode(data).encode()
            
        request = urllib.request.Request(url, data=data, headers=headers)
        response = urllib.request.urlopen(request)
        result = response.read().decode()

        #print(result)
        
        return json.loads(result)

    def login(self, username, password):
        url = self.host + "/accounts/login/"
        data = {"username": username, "password": password}
        
        data = urllib.parse.urlencode(data).encode()
        request = urllib.request.Request(url, data=data)
        response = urllib.request.urlopen(request)
        result = response.read().decode()

        #print(result)
        
        result = json.loads(result)

        if "auth" in result["content"]:
            return result["content"]["auth"]
        
        return False
    
user = User("Finlay", "Passw0rd", "http://localhost/sixthserver/api")
print(user.query("/accounts/details/", None))
