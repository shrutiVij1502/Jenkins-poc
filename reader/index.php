<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Dynamsoft Barcode Reader PHP Demo</title>
    <link type="text/css" rel="stylesheet" href="assets/css/style.css" />

    <script type="text/javascript" src="assets/js/jquery-1.11.2.js"></script>
    <script type="text/javascript">


        function fileSelected() {
            var count = document.getElementById('fileToUpload').files.length;
            if (count > 0) {
                var file = document.getElementById('fileToUpload').files[0];
                document.getElementById('filename').value = file.name;
            }
        }

        function getSelectedBarcodeTypes() {
        	var vType = 0;
        	var barcodeType = document.getElementsByName("BarcodeType");
            for (i = 0; i < barcodeType.length; i++) {
                if (barcodeType[i].checked == true)
                    vType = vType | (barcodeType[i].value * 1);
            }

            return vType;
        }

        function doReadBarcode() {
            var fd = new FormData();
            var uploadFlag = 0;

            var clsval = document.getElementById('fileUpload').getAttribute('class');
            if(clsval.indexOf('hidden') == -1) {
            	uploadFlag = 1;
            }

            if(uploadFlag) {
	            var count = document.getElementById('fileToUpload').files.length;
	            if (count > 0) {
	                var file = document.getElementById('fileToUpload').files[0];
	                fd.append('fileToUpload', file);
	            } else {
	            		return;
	            }
						} else {
							var url = document.getElementById('imageURL').value;
							if(url != null && url != "") {
								fd.append('fileToDownload', url);
							} else {
	            		return;
	            }
						}

						fd.append('uploadFlag', uploadFlag);
						fd.append('barcodetype', getSelectedBarcodeTypes());

            var xhr = new XMLHttpRequest();
            xhr.addEventListener("load", uploadComplete, false);

            if(uploadFlag) {
            	xhr.upload.addEventListener("progress", uploadProgress, false);
            	xhr.addEventListener("error", uploadFailed, false);
            	xhr.addEventListener("abort", uploadCanceled, false);
          	}
            xhr.open("POST", "/reader/readbarcode.php");
            xhr.send(fd);
        }

        function uploadProgress(evt) {
            if (evt.lengthComputable) {
                var percentComplete = Math.round(evt.loaded * 100 / evt.total);
                document.getElementById('resultBox').innerHTML = 'Upload status: Finished ' + percentComplete.toString() + '%.';
            }
            else {
                //document.getElementById('resultBox').innerHTML = 'unable to compute';
            }
        }

        function uploadComplete(evt) {
            document.getElementById('resultBox').innerHTML = evt.target.responseText;
        }

        function uploadFailed(evt) {
            alert("There was an error attempting to upload the file.");
        }

        function uploadCanceled(evt) {
            alert("The upload has been canceled by the user or the browser dropped the connection.");
        }
    </script>
</head>

<body>
    <div id="wrapper">
        <div id="dbr-php">
            <h1>
                Dynamsoft Barcode Reader PHP</h1>
            <!--<form action="">-->               
            <div class="step step1">                
                <div id="fileUpload" class="getFile">
                    <span class="num">1</span>
                    <h4>
                        Upload from local:</h4>
                    <input type="file" id="fileToUpload" onchange="fileSelected();" />
                    <input type="text" readonly="readonly" id="filename" />
                    <input type="button" />
                    <a class="clickSwitch" href="javascript:void(0);">or, Specify an URL</a>
                </div>
                <div id="fileDownload" class="hidden getFile">
                    <span class="num">1</span>
                    <h4>
                        Specify an URL:</h4>
                    <input type="text" id="imageURL"/>
                    <!--<input type="button"/>-->
                    <a class="clickSwitch" href="javascript:void(0);">or, Upload from local</a>
                </div>
            </div>
            <div class="step step2">
                <span class="num">2</span>
                <h4>
                    Barcode Types:</h4>
                <ul class="barcodeType">
                    <li class="on">
                        <label for="chkLinear">
                            <input id="chkLinear" name="BarcodeType" type="checkbox" checked="true" value="0x3FF">
                            <span>Linear</span><br />
                            <div class="imgWrapper">
                                <img src="assets/images/oned.gif" width="90" alt="Barcode Type Linear" /></div>
                        </label>
                    </li>
                    <li>
                        <label for="chkQRCode">
                            <input id="chkQRCode" name="BarcodeType" type="checkbox" value="0x4000000">
                            <span>QRCode</span><br />
                            <div class="imgWrapper">
                                <img src="assets/images/qr.gif" width="60" alt="Barcode Type QRCode" /></div>
                        </label>
                    </li>
                    <li>
                        <label for="chkPDF417">
                            <input id="chkPDF417" name="BarcodeType" type="checkbox" value="0x2000000">
                            <span>PDF417</span><br />
                            <div class="imgWrapper">
                                <img src="assets/images/pdf417.gif" width="100" height="38" alt="Barcode Type PDF417" /></div>
                        </label>
                    </li>
                    <li>
                        <label for="chkDataMatrix">
                            <input id="chkDataMatrix" name="BarcodeType" type="checkbox" value="0x8000000">
                            <span>DataMatrix</span><br />
                            <div class="imgWrapper">
                                <img src="assets/images/dm.gif" width="60" alt="Barcode Type DataMatrix" /></div>
                        </label>
                    </li>
                    <li>
                        <label for="chkDataMatrix">
                            <input id="chkDataMatrix" name="BarcodeType" type="checkbox" value="0x10000000">
                            <span>Aztec Code</span>
                            <br />
                            <div class="imgWrapper">
                                <img src="assets/images/aztec.png" width="60" alt="Aztec Code" />
                            </div>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="step step3">
                <span class="num">3</span> <a id="readBarcode" name="RecgabtnCssBarcode" onclick="doReadBarcode();">
                </a>
                <div id="resultBox">
                </div>
            </div>
            <!--</form>-->
        </div>
    </div>

    <script type="text/javascript">
        $("a.clickSwitch").click(function() {
            $(".getFile").toggleClass('hidden');
        });

        $("ul.barcodeType :checkbox").click(function() {
            $(this).parents('li').toggleClass('on');
        });

    </script>

</body>
</html>
