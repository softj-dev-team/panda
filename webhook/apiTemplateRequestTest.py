import requests
import json


# API 설정
api_url = 'https://wt-api.carrym.com:8445/api/v1/leahue/template/request'
headers = {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer F46CBA8E658BAC08965FD887B767CBC1'
}
payload = json.dumps([
    {
        "senderKey": "08dd8d04dcca412060e7004dfe38f35ab072b401",
        "templateCode": "CPS_TML_20240827203131"

    }
])
response = requests.post(api_url, headers=headers, data=payload)
print(response)
