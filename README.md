Amazon簡単検索
==============

## 概要
　2006年に構築しました。  
　当時、Amazonサイト内の検索がいまいちで、

- Amazonサイトで検索すると結果がヒットしないのに、
- GoogleからAmazonサイト内に範囲を指定して検索するとヒットする

ようなことがありました。  
そこで、「Googleの検索APIを使って各Webサイトの検索を簡単に行う」Webサービスとして、構築しました。

その後、同様の仕組みで、各種簡単検索を構築しました。
- Amazon簡単検索
- JBook簡単検索　（書店サイト）
- 紀伊國屋簡単検索　（書店サイト）
- まぐまぐ簡単検索

## 動作環境URL　※現在検索が動作しません
[こちら](http://nicher.s310.xrea.com/easy.nicher.jp/AmazonSearch/)  
※[Google Search API](http://api.google.com/GoogleSearch.wsdl)を使用していますが、これがいつの間にか使用不能になっていたようで、検索が動作しません。

## 使用技術
- Google Search API　（各Webサイトの検索）
- Amazon Web Service　（Amazonの商品情報の取得）
- Smarty　（テンプレートエンジン）
- MySQL
