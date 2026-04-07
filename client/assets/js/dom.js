/** dom.js — DOM References & App State */

const dropZone       = document.getElementById('dropZone');
const dropZoneInner  = document.getElementById('dropZoneInner');
const previewZone    = document.getElementById('previewZone');
const previewImg     = document.getElementById('previewImg');
const previewName    = document.getElementById('previewName');
const previewSize    = document.getElementById('previewSize');
const fileInput      = document.getElementById('fileInput');
const btnClear       = document.getElementById('btnClear');
const formatOptions  = document.getElementById('formatOptions');
const btnConvert     = document.getElementById('btnConvert');
const btnLabel       = document.getElementById('btnLabel');
const loadingOverlay = document.getElementById('loadingOverlay');
const loadingSub     = document.getElementById('loadingSub');
const resultCard     = document.getElementById('resultCard');
const errorCard      = document.getElementById('errorCard');
const resultSub      = document.getElementById('resultSub');
const statOrigName   = document.getElementById('statOrigName');
const statOrigSize   = document.getElementById('statOrigSize');
const statConvName   = document.getElementById('statConvName');
const statConvSize   = document.getElementById('statConvSize');
const statCompression = document.getElementById('statCompression');
const btnDownload    = document.getElementById('btnDownload');
const errorMsg       = document.getElementById('errorMsg');

// App state
let selectedFile   = null;
let selectedFormat = 'jpg';
