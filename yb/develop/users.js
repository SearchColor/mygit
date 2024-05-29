//const logger = require("./logger");
var express = require('express');
var requestIp = require('request-ip');
var router = express.Router();


// Use bcryptjs module 
//const bcrypt = require("bcryptjs");

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
  var yy = year+"-"+month+"-"+date;
  
  
  const dd =  Date();
  const str = dd.toLocaleString();
  // console.log('Production Mode' );

  // middleware that is specific to this router
  router.use(function timeLog(req, res, next) {
    
    console.log('Time: ', str );
        // const data = "get data"+str+"\n";
        // fs.appendFile('./log/'+yy+'.txt', data, (err)=>{ 
        //   if(err){ 
        //       console.log(err.message); 
        //   }
        // }) 
        
    next();
  });

  //select for m_idx
  router.get('/member/:m_idx', function (req, res) {/////////////////////////////////// Method : GET ///////////////////////////////////
    const q = req.params
    var client_IP = requestIp.getClientIp(req);
    console.log(q.m_idx);
    connection.query(`SELECT * from tbl_member where m_idx = '${q.m_idx}' `, (error, rows, fields) => {
      if (error) throw error;
      console.log('User info is: ', rows);
      return res.json({ rows })
    });
  })

  router.post('/member', function (req, res) {/////////////////////////////////// Method : POST ///////////////////////////////////
    console.log(req.body);
    var Fcode = req.body.Fcode;
    var client_IP = requestIp.getClientIp(req);
    
    if(Fcode == "J-M-001"){
      var NickName = req.body.NickName;
      var phone = req.body.phone;
      //console.log("Fcode :",req.body.Fcode );

      if(phone.length != 11){
        console.log("phone length error" );
        let result = { Fcode: `${Fcode}`, REV: `2` };
      return res.json( result )
      }
      if(NickName == ""){
        console.log("NickName is null" );
        let result = { Fcode: `${Fcode}`, REV: `3` };
      return res.json( result )
      }

      connection.query(`insert into tbl_member(phone,NickName)values(AES_ENCRYPT('${phone}',SHA2('modu',512)),'${NickName}')`, (error, rows, fields) => {
        if (error) throw error;
          console.log('result row : ', rows);
          //console.log("client IP: " +requestIp.getClientIp(req));
        //return res.json({ rows })
        var insertId = rows.insertId;
        var Req_body = JSON.stringify(req.body);
        let result = { Fcode: `${Fcode}`, REV: `1` , m_idx: rows.insertId}; 

            const data = `-------------------------------------------------------------------------------------------------------------\n`+
                         `Post Req_body : ${Req_body}\n insertid : ${insertId}\n client IP : ${client_IP}\n`+str+ 
                         "\n-------------------------------------------------------------------------------------------------------------\n";
            fs.appendFile('./log/'+yy+'.txt', data, (err)=>{ 
              if(err){ 
                  console.log(err.message); 
              }
            }) 
        return res.json( result )
      });
    }else{
      let result = 'Check the method or Fcode';
      return res.json( result )
    }
  });

  router.put('/member', function (req, res) {  /////////////////////////////////// Method : PUT ///////////////////////////////////
    var Fcode = req.body.Fcode;  
    var client_IP = requestIp.getClientIp(req);
    if(Fcode == "L-002"){  // update query
      var id = req.body.id;
      var idx = req.body.idx;
      connection.query(`update test set id ='${id}' where idx = '${idx}'`, (error, rows, fields) => {
        if (error) throw error;
          console.log('result row : ', rows);
        //return res.json({ rows })
        if(rows.affectedRows == 0){ // affectedrows 없는경우
          
          let result = { Fcode: `${Fcode}`, REV: `2`};
          return res.json( result )
        }else{ // success

            console.log('Time: ', str );
            const data = "put data"+str+"\n";
            fs.appendFile('./log/'+yy+'.txt', data, (err)=>{ 
              if(err){ 
                  console.log(err.message); 
              }
            }) 

          let result = { Fcode: `${Fcode}`, REV: `1`};
          return res.json( result )
        }
        
      });
    }else{
      let result = 'Check the method or Fcode';
      return res.json( result )
    }
  })

  router.delete('/member', function (req, res) {/////////////////////////////////// Method : DELETE ///////////////////////////////////
    var Fcode = req.body.Fcode;
    var m_idx = req.body.m_idx;
    var client_IP = requestIp.getClientIp(req);
    var Req_body = JSON.stringify(req.body);
    if(Fcode == "J-M-444"){
      connection.query(`delete from tbl_member where m_idx = '${m_idx}'`, (error, rows, fields) => {
        if (error) throw error;
          console.log('result row : ', rows);
          if(rows.affectedRows == 0){
            let result = { Fcode: `${Fcode}`, REV: `2`};
            return res.json( result )
          }else{
            let result = { Fcode: `${Fcode}`, REV: `1`};
            
            const data = `-------------------------------------------------------------------------------------------------------------\n`+
                         `Delete Req_body : ${Req_body}\n delete m_idx : ${m_idx}\n client IP : ${client_IP}\n`+str+
                         "\n-------------------------------------------------------------------------------------------------------------\n";
            fs.appendFile('./log/'+yy+'.txt', data, (err)=>{ 
              if(err){ 
                  console.log(err.message); 
              }
            }) 
          return res.json( result )
          }
      });
    }else{
      let result = 'Check the method or Fcode';
      return res.json( result )
    }
  })

  

  // router.get('/password/:password', function (req, res) {
  //   const q = req.params
  //   console.log(q.useridx);
  //   connection.query(`SELECT * from member where password = '${q.password}' `, (error, rows, fields) => {
  //     if (error) throw error;
  //     console.log('User info is: ', rows);
  //     return res.json({ rows })
  //   });
  // })


  

module.exports = router;