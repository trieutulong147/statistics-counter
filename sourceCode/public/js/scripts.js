function handleAjaxError(xhr, textStatus, error){
    if(xhr.status == '403') {
        window.location.reload();
    } else {
        alert(error);
    }
}