
#include <IRremote.h>
#include <LiquidCrystal_I2C.h>  

int RECV_PIN = 11;

IRrecv irrecv(RECV_PIN);

decode_results results;
LiquidCrystal_I2C lcd(0x27, 20, 4); 

void setup()
{
  Serial.begin(9600);
  irrecv.enableIRIn(); // Start the receiver
  lcd.init();
  lcd.backlight();
  lcd.clear();
}

void loop() {
  if (irrecv.decode(&results)) {
    lcd.clear();
    Serial.println(results.value, HEX);
    lcd.setCursor(0, 0);
    lcd.print("Got IR Sygnal...");
    delay(1000);
    lcd.setCursor(1, 1);
    lcd.print(results.value);
    translateIR();
    irrecv.resume(); // Receive the next value
    delay(200);
  }
  delay(50);
}

void translateIR() // takes action based on IR code received

// describing Car MP3 IR codes 

{

  switch(results.value)

  {

   case 0xE318261B:
   Serial.println(" OFF-            "); 
    break;
 
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
