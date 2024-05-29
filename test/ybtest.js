process.env.NODE_ENV = ( process.env.NODE_ENV && ( process.env.NODE_ENV ).trim().toLowerCase() == 'production' ) ? 'production' : 'development';
const express = require("express");
var cors = require('cors');
var users = require('./users');
var shops = require('./shops');
var reviews = require('./reviews');
const https = require('https');
const http = require('http');


const HTTPS_PORT = 22443;
const HTTP_PORT = 22226;
const fs = require('fs');

const app = express();

const options = {
    key: fs.readFileSync('./cert/Private_nopass.key'),
    cert: fs.readFileSync('./cert/star_mhgkorea_com_crt.pem')
  };
  
app.use(express.json())
app.use(express.static('develop'));
app.use(cors())
// if(process.env.NODE_ENV == 'production'){
//   app.use('/product/users', users)
// }else if(process.env.NODE_ENV == 'development'){
//   app.use('/develop/users', users)
// }
app.use('/users', users)
app.use('/shops', shops)
app.use('/reviews', reviews)


http.createServer(app).listen(HTTP_PORT);
https.createServer(options, app).listen(HTTPS_PORT);

app.get('/', (req, res) => {
    res.json({ message: `Server is running on port ${req.secure ? HTTPS_PORT : HTTP_PORT}` });
  });

  
// aaa
// app.listen(app.get("port"), app.get("host"), function () {
//     console.log("Server is running on : " + app.get("host") + ":" + app.get("port"))
// })