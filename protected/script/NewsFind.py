#encoding=utf-8

#获取baidu新闻结果

import pymongo
from pymongo import MongoClient
from bson.objectid import ObjectId

client = MongoClient('mongodb://webberdb:webberdb0304@localhost:27017/')
db = client.MyWebber
cNews = db.News
for news in cNews.find():
	if len(news['pids'])>1:
		print news