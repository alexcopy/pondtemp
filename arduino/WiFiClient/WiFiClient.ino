

#include <ESP8266WiFi.h>

const char* ssid     = "secret";
const char* password = "secret";
/*
  Public URL

  http://pondtemp.m2mcom.ru/ping?private_key=[privateKey]&lastaccess=[value]&lastupdate=[value]&sensor=[value]&timecreated=[value]&value=[value]
  ===========================
  Example:
  http://pondtemp.m2mcom.ru/ping?private_key=AJrmav1n53CaR0dr9x6o?private_key=rze6mN0xB4uG10nl9DyE&lastaccess=15.80&lastupdate=5.65&sensor=12.45&timecreated=9.06&value=25.14
  ===========================
*/
const char* host = "pondtemp.m2mcom.ru";
const char* dst_ip = "192.168.50.58";
const char* streamId   = "/ping";
const char* privateKey = "rze6mN0xB4uG10nl9DyE";
int stdDelay = 40000;  // change to 40000 after tests
int httpPort = 80;
int value = 0;
int fails = 0;
int oks = 0;
int resetattemps = 0;
int lastaccess = 0;
String srvResp = "start";

WiFiClient client;



void setup() {
  Serial.begin(115200);
  delay(10);

  // We start by connecting to a WiFi network

  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}



void loop() {
  delay(10000);
  ++value;

  Serial.print("connecting to ");
  Serial.println(host);

  if (!client.connect(dst_ip, httpPort)) {
    Serial.println("connection failed");
    delay(stdDelay);
    return;
  }

  srvResp = pingServer(srvResp);
  delay(stdDelay);

  if (value > 240 ) {  // two hours time
    value = 0;
    oks = 0;
  }

  if ((value > 20) && (fails > 20) && (oks < 2)) {
    if (resetattemps < 3) {
      resetServer();
      resetattemps++;
    } else {
      Serial.print("----Exceeded number of restart attemps-----------\n\r");
    }

  }

}

void resetServer() {

  Serial.print("----Restarting Server--and-wait-----------\n\r");

  for (int i = 0; i < 11; i++) {
    delay(stdDelay);
  }


  Serial.print("----Restarting Server-------------\n\r");

}


String pingServer(String line) {
  String responce = "";
  String url = "";
  url += streamId;
  url += "?private_key=";
  url += privateKey;
  url += "&lastaccess=";
  url += lastaccess,
         url += "&laststate=";
  url += line;

  Serial.print("Requesting URL: ");
  Serial.println(url);

  // This will send the request to the server
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" +
               "Connection: close\r\n\r\n");
  delay(1000);

  // Read all the lines of the reply from server and print them to Serial
  while (client.available()) {
    responce = client.readStringUntil('\r');
    responce.trim();
    if (responce.length() == 10) {
      lastaccess = responce.toInt();
      responce = "ok";
      break;
    }
  }
  responce.trim();

  if (responce.length() != 2) {
    responce = "fail";
    fails++;
    oks = 0;

  } else {
    oks++;
    responce = "ok";
    fails = 0;
    resetattemps = 0;
  }

  return responce;
}



