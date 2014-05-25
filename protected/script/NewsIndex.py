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

def loadCurNewsIndex():
	NewsIndex = {}
	client = MongoClient('mongodb://webberdb:webberdb0304@localhost:27017/')
	db = client.MyWebber
	cNewsIndex = db.NewsIndex
	for newsIndex in cNewsIndex.find():
		NewsIndex[newsIndex['pid']] = newsIndex['urls']
	return NewsIndex

def updateNewsIndex(NewsIndex, updatePid):
	client = MongoClient('mongodb://webberdb:webberdb0304@localhost:27017/')
	db = client.MyWebber
	cNewsIndex = db.NewsIndex
	for pid in updatePid:
		r = cNewsIndex.find_one({"pid":pid})
		if r:
			cNewsIndex.update(
				{'pid':pid},
				{'$set':
					{
						'urls': NewsIndex[pid]
					}
				}
				)
		else:
			cNewsIndex.insert(
				{
					'pid':pid,
					'urls': NewsIndex[pid]
				}
				)

NewsIndex = loadCurNewsIndex()
updatePid = []
client = MongoClient('mongodb://webberdb:webberdb0304@localhost:27017/')
db = client.MyWebber
cNews = db.News
for News in cNews.find():
	#check if match the index
	for pid in News['pids']:
		if pid not in NewsIndex:
			NewsIndex[pid] = []
		if News['url'] not in NewsIndex[pid]:
			NewsIndex[pid].append(News['url'])
			updatePid.append(pid)
updateNewsIndex(NewsIndex, updatePid)