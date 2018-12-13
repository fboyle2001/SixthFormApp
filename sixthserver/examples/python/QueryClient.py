import requests
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
            
        request = requests.post(url, data=data, headers=headers, verify="finlayboyle_me.ca-bundle")
        result = request.text

        #print(result)
        
        return json.loads(result)

    def login(self, username, password):
        url = self.host + "/accounts/login/"
        data = {"username": username, "password": password}
        
        request = requests.post(url, data=data, verify="finlayboyle_me.ca-bundle")
        result = request.text

        print(result)
        
        result = json.loads(result)

        if "auth" in result["content"]:
            return result["content"]["auth"]
        
        return False
    
user = User("DevAdmin", "admin", "https://finlayboyle.me/sixthserver/api")
print(user.query("/accounts/details/", None))
