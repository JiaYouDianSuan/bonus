/**
 * Created by jinli on 2016/8/2.
 */
function uploadFileSuccess(){
    var iPaymentPk = $("#iPaymentPk").val();
    $.ajax({
        //请求方式为get
        type:"GET",
        //json文件位置
        url:"/App/BusFinancePayment/ImportPaymentResult.php?iPaymentPk="+iPaymentPk,
        //返回数据格式为json
        //dataType: "json",
        //请求成功完成后要执行的方法
        success: function(data){
            $("#uploadFileMessage").html(data);
            navTab.reloadFlag("navTab_BusFinancePayment");
        }
    })
}
