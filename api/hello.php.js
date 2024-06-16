// api/hello.php.js

module.exports = (req, res) => {
    const { spawn } = require('child_process');
    const php = spawn('php', ['koneksi.php']);
  
    php.stdout.on('data', (data) => {
      res.status(200).send(data.toString());
    });
  
    php.stderr.on('data', (data) => {
      res.status(500).send(data.toString());
    });
  
    php.on('close', (code) => {
      if (code !== 0) {
        console.error(`PHP script exited with code ${code}`);
        res.status(500).end();
      }
    });
  };
  
