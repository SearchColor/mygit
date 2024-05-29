
var express = require('express');
var requestIp = require('request-ip');
var router = express.Router();

const fs = require('fs')

const mysql = require('mysql');
const connection = mysql.createConnection({
  host: '127.0.0.1',
  user: 'root',
  password: '54ww4543q@',
  database: 'Modu'
});

  router.use(express.json()); 
  router.use(express.urlencoded({ extended: true }));

  let curtime = new Date();
  const year = curtime.getFullYear();
  const date = curtime.getDate();
  const month = curtime.getMonth()+1;
  const hours = curtime.getHours();
  const min = curtime.getMinutes();
  const sec = curtime.getSeconds();
  
  var yy = year+"-"+month+"-"+date;
  var yyy = year+"-"+month+"-"+date+" "+hours+":"+min+":"+sec;
  
  
  const dd =  Date();
  const str = dd.toLocaleString();
  
  router.use(function timeLog(req, res, next) {
    
    console.log('Time: ', yyy );
      
    next();
  });


  router.get('/', function (req, res) {//////////////////////////////////////////////// Method : GET ////////////////////////////////////////////////


  })
  router.post('/', function (req, res) {//////////////////////////////////////////////// Method : POST ////////////////////////////////////////////////


  })
  router.put('/', function (req, res) {//////////////////////////////////////////////// Method : PUT ////////////////////////////////////////////////


  })


  router.delete('/', function (req, res) {//////////////////////////////////////////////// Method : DELETE ////////////////////////////////////////////////


  })

  
  module.exports = router;