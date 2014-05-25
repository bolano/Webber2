#encoding=utf-8

#获取baidu新闻结果

import pymongo
from pymongo import MongoClient
from bson.objectid import ObjectId

import jieba
import jieba.analyse
from optparse import OptionParser

import urllib
import urllib2
from bs4 import BeautifulSoup

import httplib
import requests

import time
import operator

import traceback
import math

import datetime

def getBaiduNews(realname,company,pid):
	#http://news.baidu.com/ns?word=&ie=utf-8&sr=0&cl=2&rn=20&tn=news&ct=0&clk=sortbytime
	url = "http://news.baidu.com/ns"

	if company=="":
		return

	querys = {'word': company+" "+realname,
							   'ie': "utf-8",
							   'sr':"0",
							   'cl':"2",
							   'rn':"20",
							   'tn':"news",
							   'ct':'0',
							   'clk':'sortbytime'}
	"""
	Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
	Accept-Encoding:gzip,deflate,sdch
	Accept-Language:zh-CN,zh;q=0.8,en;q=0.6
	Cache-Control:max-age=0
	Connection:keep-alive
	Cookie:BAIDUID=D788F0266179D2BEF9DB73E4960AE6FB:FG=1; bdshare_firstime=1353663171984; BDUT=a7nq06B6FE30D9D41491C060335A10E7E2E3139dd2798ad4; pgv_pvi=3101148160; locale=zh; BAIDU_WISE_UID=D474F85B27B9DF9618723CC640EA53C8; BAIDU_WAP_WENKU=c65d1522af45b307e871973c_1_3_500_2_1_0_wml_wk; PSPVTEST=45; cflag=65535:1; MCITY=-%3A; NBID=6E699556D56A56CD1A236682293EBA38:FG=1; Hm_lvt_55b574651fcae74b0a9f1cf9c8d7c93a=1398235571,1398237601,1398238522,1398262047; Hm_lpvt_55b574651fcae74b0a9f1cf9c8d7c93a=1398262059; H_PS_PSSID=
	Host:news.baidu.com
	User-Agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36
	"""
	headers = {
			   "Accept": 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
			   "Accept-Encoding" : 'gzip,deflate,sdch',
			   "Accept-Language" : 'zh-CN,zh;q=0.8,en;q=0.6',
			   "Connection" : 'keep-alive',
			   "Host" : 'news.baidu.com',
			   "Referer" : 'http://news.baidu.com/',
			   "User-Agent" : 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36'
			   }

	r = requests.get(url, params=querys, headers=headers)
	#print(r.url)
	soup = BeautifulSoup(r.content, from_encoding="utf-8")

	allnews = soup.find_all('li',class_="result")

	for news in allnews:
		url = news.h3.a.get('href')
		title = news.h3.a.get_text()
		source = news.span.get_text()
		summary = news.div.get_text()
		if url not in urlMap:
			saveDB(pid, realname, company, url, title, source, summary)
		else:
			#check the person already in the list
			if pid in urlMap[url]:
				continue
			else:
				addUrlPerson(url, pid)
				urlMap[url].append(pid)

def addUrlPerson(url, pid):
	client = MongoClient('mongodb://webberdb:webberdb0304@localhost:27017/')
	db = client.MyWebber
	cNews = db.News
	cNews.update(
		{"url":url},
		{"$push":
			{"pids":pid}
		}
	)

#存入数据库
def saveDB(pid, realname, company, url, title, source, summary):
	client = MongoClient('mongodb://webberdb:webberdb0304@localhost:27017/')
	db = client.MyWebber
	cNews = db.News
	sourceSplit = source.split()
	if len(sourceSplit) == 3:
		source = sourceSplit[0]
		timeStr = sourceSplit[1]+" "+sourceSplit[2]+".000"
	else:
		source = ""
		timeStr = sourceSplit[0]+" "+sourceSplit[1]+".000"
	cNews.insert(
		{
		"pids" : [pid],
		"realname": realname,
		"company": company,
		"query": company+" "+realname,
		"url": url,
		"title" : title,
		"source" : source,
		"date" : datetime.datetime.strptime(timeStr, "%Y-%m-%d %H:%M:%S.%f"),
		"summary" : summary
		})
	#update map
	global urlMap
	urlMap[url] = [pid]

#gen mapping from news url to id from existing records
def genNewsMap():
	urlMap = {}
	client = MongoClient('mongodb://webberdb:webberdb0304@localhost:27017/')
	db = client.MyWebber
	cNews = db.News
	for News in cNews.find():
		urlMap[News['url']] = News['pids']

	return urlMap

urlMap = genNewsMap()
client = MongoClient('mongodb://webberdb:webberdb0304@localhost:27017/')
db = client.MyWebber
cPerson = db.Person
for person in cPerson.find():
	print person["realname"]
	getBaiduNews(person["realname"],person["company"], person["id"])