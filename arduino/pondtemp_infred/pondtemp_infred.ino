
#include <IRremote.h>
#include <IRremoteInt.h>
 
#include <SoftwareSerial.h>
#include <Wire.h> 
#include <LiquidCrystal_I2C.h> //ЖК дисплей 
#include <DHT.h> // Сенсор на улице  

// set the DHT Pin
#define DHTPIN 8
#define DHTTYPE DHT11
#define DST_IP "192.168.50.58"


int tempPin = 0;        //pond analog pin
int RECV_PIN = 11;      //Пин для ИК порта 
int prevSensorVal = 0;  //
bool pond = false;      //
int counter = 0;        //
float totalPond = 0;    // the running total
float averagePond = 0;  // the averageint prevSensorVal = 0;

IRrecv irrecv(RECV_PIN);            //инициализация ик порта 
decode_results results;             // create instance of 'decode_results'
LiquidCrystal_I2C lcd(0x27, 20, 4); // set the LCD address to 0x27 for a 16 chars and 2 line display
SoftwareSerial esp8266(2, 3);       // RX, TX
DHT dht(DHTPIN, DHTTYPE);  


void setup() {
  lcd.init();
  lcd.backlight();
  dht.begin();
  Serial.begin(19200);
  irrecv.enableIRIn(); // Start the IR receiver
  esp8266.begin(115200);
  readEsp(); // read comport output from esp(wifi module)
  wifiModulePrepare();
  prevSensorVal = digitalRead(7);
  lcd.clear();
  pinMode(7, INPUT_PULLUP); //button
  pinMode(6, INPUT_PULLUP); //fltr3
  pinMode(5, INPUT_PULLUP); //pond level
}

void loop() {
  lcd.setCursor(0, 0);
  int sensorVal = digitalRead(7);
  showTempAndHumid();
  if (sensorVal != prevSensorVal) {
    pond = pond ? false : true;
    prevSensorVal = 1;
    lcd.clear();
    delay(1000);
  }

  // отправлять на сервер каждые  counter / 10 = секунд
  if (counter > 300) {
    sendData();
    counter = 1;
    totalPond =  averagePond;
    lcd.clear();
  }

  totalPond += pondTemp();
  counter++;
  averagePond = totalPond / counter;

 
  if (irrecv.decode(&results)) // have we received an IR signal?
  {
    translateIR(); 
    lglcd("Got IR Sygnal...");
    delay(1000);
    lcd.clear();
    lcd.setCursor(1, 1);
    lcd.print(results.value);
    Serial.println(results.value,HEX);
    delay(1000);
    irrecv.resume(); // receive the next value
    lcd.clear();
    showTempAndHumid();
  } 
   prevSensorVal = digitalRead(7);
   delay(100);
}

void showTempAndHumid() {

  if (pond == true) {
    lcd.print("PondTemp: ");
    lcd.print(averagePond);
  } else {
    lcd.print("Temp: ");
    lcd.print(dht.readTemperature());
  }

  lcd.print((char) 223);
  lcd.print("C");
  lcd.setCursor(0, 1);
  lcd.print("Humidity: ");
  lcd.print(dht.readHumidity());
  lcd.print("%");

}

float pondTemp() {
  float val = analogRead(tempPin);
  float mv = (val / 1024.0) * 5000;
  return mv / 10;
}


void wifiModulePrepare() {

  lglcd("Starting WIFI...");
  delay(2000);
  esp8266.println("AT+RST"); // сброс и проверка, если модуль готов
  readEsp();
  lglcd("Reseting WiFi....");
  delay(2000);
  esp8266.println("AT+CWMODE=1");
  delay(2000);
  if (esp8266.available()) {
    Serial.println("WiFi - Module is ready");
    lglcd("WiFi - Module is ready");

  } else {
    Serial.println("Module dosn't respond.");
    lglcd("Module dosn't respond.");
    lcd.setCursor(1, 1);
    lcd.println("Please reset.");
    while (1);
  }

  delay(1000);
  // try to connect to wifi
  boolean connected = false;
  lcd.setCursor(0, 1);
  for (int i = 0; i < 10; i++) {
    if (connectWiFi()) {
      connected = true;
      lglcd("Wi-Fi connected");
      break;
    }
  }
  if (!connected) {
    lglcd("WiFi failed");
    while (1);
  }
  delay(2000);
  esp8266.println("AT+CIPMUX=0");
}

void lglcd(String txt) {
  lcd.clear();
  lcd.setCursor(0, 0);
  txt.trim();
  lcd.println(txt);
}

bool connectWiFi() {
  lglcd("Conn to WIFI....");
  readEsp();
  esp8266.println("AT+CWJAP=\"redkot\",\"Gorkogo177\"");
  delay(2000);
  return true;
}

String readEsp() { //rename to check for wifi status read
  String buf;
  while (esp8266.available()) {
    delay(50);
    // The esp has data so display its output to the serial window
    char c = esp8266.read(); // read the next character.
    buf += c;
    Serial.write(c);
    delay(50);
  }
  Serial.print(buf);
  return buf;
}



String sendData() {
  lglcd("Sending Data....");
  esp8266.println("AT+CIPMUX=0");
  //Open a connection to the web server
  String cmd = "AT+CIPSTART=\"TCP\",\""; 
  cmd += DST_IP;
  cmd += "\",80";
  esp8266.println(cmd);
  delay(600);
  cmd = "GET /receiver?ptemp=";
  cmd += averagePond;
  cmd += "&shedtemp=";
  cmd += dht.readTemperature();
  cmd += "&strtemp=";
  cmd += 0;
  cmd += "&shedhumid=";
  cmd += dht.readHumidity();
  cmd += "&streethumid=";
  cmd += 0;
  cmd += "&fltr3=";
  cmd += digitalRead(6);
  cmd += "&pndlvl=";
  cmd += digitalRead(5);
  cmd += " HTTP/1.0\r\n";
  cmd += "Host: pondtemp.m2mcom.ru\r\n\r\n";

  esp8266.print("AT+CIPSEND=");
  esp8266.println(cmd.length());

  //Look for the > prompt from the esp8266
  if (esp8266.find(">")) {
    //Send our http GET request
    esp8266.println(cmd);
    delay(7000);
    esp8266.println("\r\n");
    delay(1000);
    esp8266.println("AT+CIPCLOSE");
  } else {
    //Something not work...
    esp8266.println("AT+CIPCLOSE");
  }
  delay(1000);
  String resp = readEsp();
  
  lglcd(resp);
  return resp;
}

void translateIR() // takes action based on IR code received

// describing Car MP3 IR codes 

{

  switch(results.value)

  {

  case 0xFFA25D:  
    Serial.println(" CH-            "); 
    break;

  case 0xFF629D:  
    Serial.println(" CH             "); 
    break;

  case 0xFFE21D:  
    Serial.println(" CH+            "); 
    break;

  case 0xFF22DD:  
    Serial.println(" PREV           "); 
    break;

  case 0xFF02FD:  
    Serial.println(" NEXT           "); 
    break;

  case 0xFFC23D:  
    Serial.println(" PLAY/PAUSE     "); 
    break;

  case 0xFFE01F:  
    Serial.println(" VOL-           "); 
    break;

  case 0xFFA857:  
    Serial.println(" VOL+           "); 
    break;

  case 0xFF906F:  
    Serial.println(" EQ             "); 
    break;

  case 0xFF6897:  
    Serial.println(" 0              "); 
    break;

  case 0xFF9867:  
    Serial.println(" 100+           "); 
    break;

  case 0xFFB04F:  
    Serial.println(" 200+           "); 
    break;

  case 0xFF30CF:  
    Serial.println(" 1              "); 
    break;

  case 0xFF18E7:  
    Serial.println(" 2              "); 
    break;

  case 0xFF7A85:  
    Serial.println(" 3              "); 
    break;

  case 0xFF10EF:  
    Serial.println(" 4              "); 
    break;

  case 0xFF38C7:  
    Serial.println(" 5              "); 
    break;

  case 0xFF5AA5:  
    Serial.println(" 6              "); 
    break;

  case 0xFF42BD:  
    Serial.println(" 7              "); 
    break;

  case 0xFF4AB5:  
    Serial.println(" 8              "); 
    break;

  case 0xFF52AD:  
    Serial.println(" 9              "); 
    break;

  default: 
    Serial.println(" other button   ");

  }

  delay(500);


} //END translateIR









