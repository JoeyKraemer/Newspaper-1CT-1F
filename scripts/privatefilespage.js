function openContext(i, filePath){
    const caller = document.getElementById(i).getElementsByClassName('options')[0];
    const menu = document.createElement('div');
    const background = document.createElement('div');
    const downloadtxt = document.createElement('a');
    const deletetxt = document.createElement('a');

    // so it only sends the file and not the file path (for account safety reasons)
    const fileName = filePath.split('\\').pop().split('/').pop();

    downloadtxt.innerHTML = 'view';
    downloadtxt.href = filePath;
    menu.appendChild(downloadtxt);

    deletetxt.innerHTML = 'delete';
    deletetxt.href = "javascript:deleteFile('" + fileName + "')";
    menu.appendChild(deletetxt);

    background.appendChild(menu);

    background.style.position = 'fixed';
    background.style.width = '100vw';
    background.style.height = '100vh';
    background.onclick = function(){this.remove();};

    menu.style.position = 'relative';

    rect = caller.getBoundingClientRect()

    menu.style.left = rect.left + "px";
    menu.style.top = rect.bottom + "px";
    menu.className = "contextMenu";
    menu.onclick = event=>event.stopPropagation();
    // assigns the attibutes onto menu
    styles = [["width", "fit-content"], ["height", "fit-content"], ["background-color", "lightGrey"], ['padding-bottom', '1vw'], ['padding-left', '0.5vw'], ['padding-right', '1vw']];
    styles.forEach(item => {
       menu.style[item[0]] = item[1];
    });

    document.body.appendChild(background);
}

function uploadFile(){
    document.getElementById('fileInput').click();
}
function deleteFile(fileName){
    document.getElementById('textInput').value = fileName;
    document.getElementById("requestType").value = "delete";
    document.getElementById("form").submit();
}

// auto submits the form when the Input for files changes values (aka. a file got put in);
document.getElementById("fileInput").onchange = function() {
    document.getElementById("requestType").value = "upload";
    document.getElementById("form").submit();
};