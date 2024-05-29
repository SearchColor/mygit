
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
  const millisec = curtime.getMilliseconds();
  var yy = year+"-"+month+"-"+date;
  var yyy = year+"-"+month+"-"+date+" "+hours+":"+min+":"+millisec;
  
  
  const dd =  Date();
  const str = dd.toLocaleString();
  
  router.use(function timeLog(req, res, next) {
    
    console.log('Time: ', yyy );
      
    next();
  });


  router.get('/', function (req, res) {///////////////////////////////////Shopinfo GET ///////////////////////////////////
    const q = req.query;

    var Req_pa = JSON.stringify(q);
    console.log('Request get params : ',Req_pa);
    if(q.Fcode =="O-S-001"){

      connection.query(`select * from tbl_shopinfo`, (error, shopinfo_rows, fields) => {
        if (error) throw error;
        console.log('Request params : ',Req_pa);

        connection.query('select * from tbl_shopCloseday', function (error, shopclose_rows, fields) {
            if (error) throw error;

            let result = { Fcode: `${q.Fcode}`, REV: `1` , shopinfo_rows: shopinfo_rows , shopclose_rows: shopclose_rows}; 
            return res.json(result)
          });
      });
    }else if(q.Fcode =="O-S-002"){

        connection.query(`select * from tbl_category`, (error, category_rows, fields) => {
            if (error) throw error;
            console.log('Request params : ',Req_pa);

            connection.query('select * from tbl_shopmenu', function (error, shopmenu_rows, fields) {
                if (error) throw error;
    
                let result = { Fcode: `${q.Fcode}`, REV: `1` , category_rows: category_rows , shopmenu_rows: shopmenu_rows}; 
                return res.json(result)
              });
          });
    }else if(q.Fcode == "O-S-003"){
      var m_idx = q.m_idx;
      var scode = q.scode;

      connection.query(`select status from tbl_favorite where m_idx='${m_idx}' and scode='${scode}'`, (error, category_rows, fields) => {
          if (error) throw error;
          console.log('category_rows : ',category_rows);
          if(category_rows.length === 0 ){
            let result = { Fcode: `${q.Fcode}`, REV: `2`};
          return res.json(result)
          }

          let result = { Fcode: `${q.Fcode}`, REV: `1` , status: category_rows[0].status}; 
          return res.json(result)

          
        });
  }else{
      let result = { Fcode: `${q.Fcode}`, REV: `444`}; // Check the method or Fcode
      return res.json( result )
    }
    
  })

  router.put('/', function (req, res) {//////////////////////////////////////////////// Method : PUT ////////////////////////////////////////////////
    var Fcode = req.body.Fcode;
    var m_idx = req.body.m_idx;
    var scode = req.body.scode;
    var client_IP = requestIp.getClientIp(req);
    var Req_body = JSON.stringify(req.body);
    if(Fcode == "O-S-007"){
      connection.query(`select count(fa_idx) as count from tbl_favorite where m_idx='${m_idx}'and scode='${scode}'`, (error, selectrows, fields) => {
        if (error) throw error;
          console.log('select count row : ', selectrows[0].count);
          if(selectrows[0].count == 0){
              connection.query(`insert into tbl_favorite(m_idx,scode,status)values('${m_idx}','${scode}',1) `, (error, insertrows, fields) => {
                if (error) throw error;
                console.log('insert row : ', insertrows);
                let result = { Fcode: `${Fcode}`, REV: `1` , status: `1`};
                return res.json(result)
              });
          }else{
            connection.query(`select status from tbl_favorite where m_idx='${m_idx}'and scode='${scode}'`, (error, selectrows, fields) => {
              if (error) throw error;
              console.log('select status row : ', selectrows[0].status);

              if(selectrows[0].status == 1){
                connection.query(`update tbl_favorite set status = '0' where m_idx='${m_idx}' and scode='${scode}' `, (error, updaterows, fields) => {
                  if (error) throw error;
                  console.log('update row : ', updaterows);
                  let result = { Fcode: `${Fcode}`, REV: `1` , status: `0`};
                  return res.json(result)
                });
              }else if(selectrows[0].status == 0){
                connection.query(`update tbl_favorite set status = '1' where m_idx='${m_idx}' and scode='${scode}' `, (error, updaterows, fields) => {
                  if (error) throw error;
                  console.log('update row : ', updaterows);
                  let result = { Fcode: `${Fcode}`, REV: `1` , status: `1`};
                  return res.json(result)
                });
              }

            });
            
            
          }
            
               
      });
    }else{
      let result = 'Check the method or Fcode';
      return res.json( result )
    }
  })

  

  module.exports = router;