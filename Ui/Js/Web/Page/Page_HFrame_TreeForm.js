/**
 * Created by jinli on 2016/6/30.
 */
function addParamValue(url,paramName,paramValue) {
    var oUrl = url;
    var nUrl = "";
    if(url.indexOf(paramName) == -1){
        nUrl = url+"&"+paramName+"="+paramValue;
    }else{
        var re=eval('/('+ paramName+'=)([^&]*)/gi');
        nUrl = oUrl.replace(re,paramName+'='+paramValue);
    }
    return nUrl;
}

function addTreeNode(el){
    var sValue = $("#TREE_NODE_PK").attr("value");
    if(typeof(sValue) == "undefined") sValue=0;
    var url = addParamValue($(el).attr("href"),"TREE_NODE_PK",sValue)
    $(el).attr("href",url);
}