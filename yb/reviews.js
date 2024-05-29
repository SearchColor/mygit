
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


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  router.get('/Fcode/:Fcode', function (req, res) {///////////////////////////////////Shopinfo GET ///////////////////////////////////
    const q = req.params
    var Req_pa = JSON.stringify(q);
    
    if(q.Fcode =="O-R-001"){

      
    }else{

        let result = { Fcode: `${q.Fcode}`, REV: `444`}; // Check the method or Fcode
        return res.json( result )
    }
  })


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  router.post('/', function (req, res) {/////////////////////////////////// Method : POST /////////////////////////////////////////////////////////////
    console.log(req.body);
    var Fcode = req.body.Fcode;
    var client_IP = requestIp.getClientIp(req);
    var m_idx = req.body.m_idx;
    var content = req.body.content;
    var imagepath = req.body.imagepath;
    var Req_body = JSON.stringify(req.body);
    if(Fcode == "O-R-001"){
      
        //console.log("Fcode :",req.body.Fcode );

        if(content.length >= 100){
            console.log("content length too long : " ,content.length  );
            let result = { Fcode: `${Fcode}`, REV: `2` };
        return res.json( result )
        }
            connection.query(`insert into tbl_review(m_idx,content,imagepath,idate)values('${m_idx}','${content}','${imagepath}','${yyy}')`, (error, rows, fields) => {
            if (error) throw error;
                console.log('result row : ', rows);
                console.log('Request body : ',Req_body);

                let result = { Fcode: `${Fcode}`, REV: `1` ,rv_idx :rows.insertId}; // Check the method or Fcode
                return res.json( result )
            });
    }else{
      let result = { Fcode: `${Fcode}`, REV: `444`}; // Check the method or Fcode
      return res.json( result )
    }
  });

  router.put('/', function (req, res) {//////////////////////////////////////////////// Method : PUT ////////////////////////////////////////////////
    var Fcode = req.body.Fcode;
    var rv_idx = req.body.rv_idx;

    if(Fcode == "O-R-033"){
        connection.query(`select rv_private from tbl_review where rv_idx='${rv_idx}'`, (error, selectrows, fields) => {
            if (error) throw error;
            console.log('select status row : ', selectrows);
            if(selectrows.length === 0){
                let result = { Fcode: `${Fcode}`, REV: `2`};
                return res.json(result)
            }

            if(selectrows[0].rv_private == 1){
              connection.query(`update tbl_review set rv_private = '0' where rv_idx='${rv_idx}'`, (error, updaterows, fields) => {
                if (error) throw error;
                console.log('update row : ', updaterows);
                let result = { Fcode: `${Fcode}`, REV: `1` , rv_private: `0`};
                return res.json(result)
              });
            }else if(selectrows[0].rv_private == 0){
              connection.query(`update tbl_review set rv_private = '1' where rv_idx='${rv_idx}'`, (error, updaterows, fields) => {
                if (error) throw error;
                console.log('update row : ', updaterows);
                let result = { Fcode: `${Fcode}`, REV: `1` , rv_private: `1`};
                return res.json(result)
              });
            }
          });
    }else if(Fcode == "O-R-444"){
        connection.query(`update tbl_review set rv_status ='1' where rv_idx='${rv_idx}'`, (error, rows, fields) => {
            if (error) throw error;
              console.log('result row : ', rows);
            if(rows.affectedRows == 0){ // affectedrows 없는경우
              
              let result = { Fcode: `${Fcode}`, REV: `2`}; //해당 rv_idx row없음
              return res.json( result )
            }else if(rows.changedRows ==0){
              let result = { Fcode: `${Fcode}`, REV: `3`}; //이미 rv_status value = 1 
              return res.json( result )
            }else{ // success
              let result = { Fcode: `${Fcode}`, REV: `1`}; //success
              return res.json( result )
            }
            });

    }else{
      let result = 'Check the method or Fcode';
      return res.json( result )
    }
  })



  module.exports = router;