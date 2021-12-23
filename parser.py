import sys
import re
import xml.dom.minidom
import mysql.connector
def parse():
    doc=xml.dom.minidom.parse(sys.argv[1])
    productName=""
    productDescription=""
    productPrice=float(0)
    productURL=""
    imageURL=""
    reviewScore=float(0)
    siteName=""

    metaList=doc.getElementsByTagName("meta")
    for meta in metaList:
        if meta.hasAttribute("property"):
            if meta.getAttribute("property")=="og:site_name":
                if meta.hasAttribute("content"):
                    siteName=meta.getAttribute("content")



    if(siteName=="Walmart.com"):
        spanList=doc.getElementsByTagName("span")
        for span in spanList:    
        
            if span.hasAttribute("itemprop"):
                if span.getAttribute("itemprop")=="price":
                    productPrice=round(float(span.firstChild.nodeValue.lstrip("$")),2)
        
            if span.hasAttribute("class"):
                if span.getAttribute("class")=="f7 rating-number":
                   reviewScore=float(span.firstChild.nodeValue.strip("()"))    
        
        for meta in metaList:
            if meta.hasAttribute("property"):
                if meta.getAttribute("property")=="og:title":
                    if meta.hasAttribute("content"):
                        productName=meta.getAttribute("content").rstrip("- Walmart.com")
    
                if(meta.getAttribute("property")=="og:url"):
                    if(meta.hasAttribute("content")):
                        productURL=meta.getAttribute("content");
                if(meta.getAttribute("property")=="og:image"):
                    if(meta.hasAttribute("content")):
                        imageURL=meta.getAttribute("content");
        scriptList = doc.getElementsByTagName("script")
      
        for script in scriptList:
            if script.childNodes.length>0:
                if script.firstChild.nodeValue is not None:
                    r1=script.firstChild.nodeValue
                    r2=re.search("\"description\":\"(.*)\"\,\"model\":",r1)
                    if r2 is not None:
                        r3=r2.group(1)
                        productDescription= r3
    else:
        siteName="quill.com"
        for h in doc.getElementsByTagName("h1"):
            if h.hasAttribute("class"):
               if h.getAttribute("class")=="skuName":
                  productName=h.firstChild.nodeValue
        for i in doc.getElementsByTagName("img"):
             if i.hasAttribute("id"):
                if i.getAttribute("id")=="SkuPageMainImg":
                   if i.hasAttribute("src"):
                      imageURL="https:"+i.getAttribute("src")
                      break;
        for span in doc.getElementsByTagName("span"):
            if span.hasAttribute("id"):
                if span.getAttribute("id")=="productRating":
                    reviewScore=float(span.firstChild.nodeValue)
            if span.hasAttribute("class"):
                if span.getAttribute("class")=="price red skuPriceLabel txtBold":
                    productPrice=span.firstChild.nodeValue.strip().lstrip("$")
                    if productPrice != '':
                        productPrice=float(productPrice.replace(',',''))
                    else:
                        for span in doc.getElementsByTagName("span"):
                            if span.hasAttribute("class"):
                                if span.getAttribute("class")=="priceupdate":
                                    productPrice=float((span.firstChild.nodeValue).replace(',',''))
                                    break;
         
        for div in doc.getElementsByTagName("div"):
            if div.hasAttribute("data-tab"):
                if div.getAttribute("data-tab")=="tbDescription":
                    for span in div.getElementsByTagName("span"):
                        if span.firstChild:
                           if span.firstChild.nodeType==span.firstChild.TEXT_NODE:
                              productDescription+=(span.firstChild.nodeValue).strip()
                              productDescription+="."
        for link in doc.getElementsByTagName("link"):
            if link.hasAttribute("rel"):
               if link.getAttribute("rel")=="canonical":
                  if link.hasAttribute("href"):
                     productURL=link.getAttribute("href")
    
    dict={"pn":productName,"pd":productDescription,"pp":productPrice,"pu":productURL,"iu":imageURL,"rs":reviewScore}
    return dict

def insert(cursor,dict):
    query = 'INSERT INTO productDB(product_name,product_description,product_price,product_url,image_url,review_score) VALUES (%s,%s,%s,%s,%s,%s)'
    cursor.execute(query, (dict["pn"],dict["pd"],dict["pp"],dict["pu"],dict["iu"],dict["rs"]))

def update(cursor,dict):
    query='UPDATE productDB SET product_name=%s,product_description=%s,product_price=%s,image_url=%s,review_score=%s WHERE product_url =%s'
    cursor.execute(query,(dict["pn"],dict["pd"],dict["pp"],dict["iu"],dict["rs"],dict["pu"]))



dict=parse()

try:
    cnx = mysql.connector.connect(host='localhost', user='aa2789', password='password123', database='ShopFinal')
    cursor = cnx.cursor()
    iter=int(sys.argv[2])

    if(iter>1):
        update(cursor,dict)
        cnx.commit()
    else:
       insert(cursor,dict)
       cnx.commit()


    cursor.close()
except mysql.connector.Error as err:
    print(err)
finally:
    try:
        cnx
    except NameError:
        pass
    else:
        cnx.close()
