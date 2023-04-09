import requests as s
import re

def login():
    print("正在请求登录页面...")
    login_page_url = "https://accounts.pixiv.net/login"
    login_html = s.get(login_page_url)
    print("正在解析")
    post_key = get_post_key(login_html.text)

    login_url = "https://accounts.pixiv.net/api/login?lang=zh"
    pixiv_id, password = get_account()
    login_data = s.post(url=login_url, data={
        "pixiv_id": pixiv_id,
        "password": password,
        "captcha": "",
        "g_recaptcha_response": "",
        "post_key": post_key,
        "source": "accounts",
        "ref": "",
        "return_to": "http://www.pixiv.net/"
    })
    print(login_data.text)


def get_post_key(login_html):
    # reg = r'name="post_key" value="(.*?)"'
    # key_re = re.compile(reg)
    # key_list = re.findall(key_re, login_html)
    # if len(key_list) == 1:
    #     return key_list[0]
    # else:
    #     raise Exception("post key can not found")
    return "6LfF1dcZAAAAAOHQX8v16MX5SktDwmQINVD_6mBF"


def get_account():
    return "hhrh1123@outlook.com", "wasd1029wasd"

login()