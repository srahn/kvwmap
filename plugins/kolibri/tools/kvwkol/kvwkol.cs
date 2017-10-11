using System;
using System.Configuration;
using System.Collections.Generic;
using System.Text;
using System.IO;
using System.Text.RegularExpressions;

namespace myprogram
{
    class WriteTextFile
    {
        static void Main(string[] args)
        {
            //Lade Einstellungen
            string debugStr = ConfigurationManager.AppSettings["debug"];
            bool debug = Convert.ToBoolean(debugStr);
            if (debug) Console.WriteLine("debug: " + debug);

            string outputPath = ConfigurationManager.AppSettings["outputPath"];
            if (debug) Console.WriteLine("outputPath: " + outputPath);

            //string text = "kvwkol://FlurstKennz=Kennzeichen1;Kennzeichen2;...;KennzeichenN/";
            //string[] text = { "kvwkol://Bundeslaender=13;13&Gemarkungen=4186;4186&Flur=1;1&Zähler=20;21&Nenner=;2/" };
            //string[] text = { "kvwkol://FlurstKennz=13176300600004______,131763006000050001__" };
            //string[] text = { "kvwkol://FlurstKennz=132658001001200004__,13265800100121______" };
            string[] text = args;

            //Prüfe, ob etwas übergeben wurde
            if (text.Length > 0)
            {
                //Schneide alles vor (und inkl.) // weg
                //string textWOprotocol = args[0].Substring(args[0].IndexOf("//") + 2);
                string textWOprotocol = text[0].Substring(text[0].IndexOf("//") + 2);

                //Prüfe, ob auch Parameter übergeben wurden
                if (textWOprotocol.Length > 0)
                {
                    if (debug) Console.WriteLine("Übergebene Parameter: " + textWOprotocol);

                    //Lösche mögliches / am Ende
                    if (textWOprotocol.IndexOf("/") > 0)
                    {
                        textWOprotocol = textWOprotocol.Substring(0, textWOprotocol.Length - 1);
                        //Console.WriteLine("Values semikolongetrennt ohne /: " + URLvalues);
                    }

                    //Schneide alles vor (und inkl.) "=" weg
                    textWOprotocol = textWOprotocol.Substring(textWOprotocol.IndexOf("=") + 1);

                    //Explode mit , (falls anderes Trennzeichen in URL verwandt werden soll/würde)
                    String[] URLvaluesArr = textWOprotocol.Split(',');
                    //foreach (var URLvalue in URLvaluesArr)
                    //    Console.WriteLine(URLvalue);
                    if (debug) Console.WriteLine("Value 0: " + URLvaluesArr[0]);
                    if (debug) Console.WriteLine("Values semikolongetrennt: " + string.Join(";", URLvaluesArr));

                    string zahl = "000001";
                    string tmp3 = Regex.Replace(zahl, "(?<=^)0+", " ");
                    if (debug) Console.WriteLine("Test: '" + tmp3 + "'");
                    tmp3 = Regex.Replace(zahl, "w(?<=^)0", " ");
                    if (debug) Console.WriteLine("Test: '" + tmp3 + "'");

                    //https://stackoverflow.com/questions/9436381/c-sharp-regex-string-extraction
                    //https://stackoverflow.com/questions/17096494/counting-letters-in-string
                    //var groups = Regex.Match(zahl, "(?<=^)0+").Groups;
                    //var x1 = groups[1].Value;
                    //if (debug) Console.WriteLine("x1: '" + x1 + "'");

                    string[] stringArrToWrite = new string[URLvaluesArr.GetLength(0)+1];
                    stringArrToWrite[0] = "$Land$;$Gemarkung$;$Flur$;$Zähler$;$Nenner$";

                    for (int i = 0; i < URLvaluesArr.GetLength(0); i++)
                    {
                        //Extrahiere und Schreibe Land (1-2) - geht nicht mit "10"!!!!
                        stringArrToWrite[i+1] = "$      " + URLvaluesArr[i].Substring(0, 2).Replace("0", " ") + "$;";
                        //Extrahiere und Schreibe Gemarkung (3-6)
                        stringArrToWrite[i+1] += "$" + URLvaluesArr[i].Substring(2, 4).Replace("0", " ") + "$;";
                        //Extrahiere und Schreibe Flur (7-9)
                        if (debug) Console.WriteLine("7-9: " + URLvaluesArr[i].Substring(6, 3));
                        string tmp = Regex.Replace(URLvaluesArr[i].Substring(6, 3), "(?<=^)0+", " ");
                        stringArrToWrite[i+1] += "$" + tmp + "$;";
                        //Extrahiere und Schreibe Zähler (10-14)
                        tmp = Regex.Replace(URLvaluesArr[i].Substring(9, 5), "(?<=^)0+", " ");
                        stringArrToWrite[i+1] += "$" + tmp + "$;";
                        //Extrahiere und Schreibe Nenner (15-18)
                        if (debug) Console.WriteLine("15-18: " + URLvaluesArr[i].Substring(14, 4));
                        tmp = Regex.Replace(URLvaluesArr[i].Substring(14, 4), "(?<=^)0+", " ");
                        stringArrToWrite[i+1] += "$" + tmp + "$;";
                    }

                    /* deprecated begin
                    //Lese Parameterarrays und schreiben Einträge in jeweils andere Zeilen und füge sie mit ; zusammen (und füge auch ' an den entsprechenden Stellen ein)
                    //Console.WriteLine(string.Join(",", paramArr[3]));
                    //Console.WriteLine("Mache 2 Zeilen draus");
                    string[] stringArrToWrite = new string[paramArr[0].GetLength(0)];

                    for (int j = 0; j < paramArr.GetLength(0); j++)
                    {
                        //Console.WriteLine(string.Join(",", paramArr[j]));
                        //Explode mit ; (falls anderes Trennzeichen in URL verwandt werden soll/würde)
                         for (int k = 0; k < paramArr[0].GetLength(0); k++)
                        {
                            if (stringArrToWrite[k] == null || stringArrToWrite[k].Length == 0) stringArrToWrite[k] = "'" + paramArr[j][k];
                            else if (j == paramArr.GetLength(0) - 1) stringArrToWrite[k] = stringArrToWrite[k] + "';'" + paramArr[j][k] + "';";
                            else stringArrToWrite[k] = stringArrToWrite[k] + "';'" + paramArr[j][k];
                        }
                    }

                   
                    //Implode mit Semikolon
                    //System.IO.File.WriteAllText(@"D:\_dev\GDIS\temp\test.text", string.Join(";", URLvalues));
                    string URLvalues = string.Join(";", URLvaluesArr);
                    //Console.WriteLine("Values semikolongetrennt: " + URLvalues);

                    //Füge '' ein
                    URLvalues = URLvalues.Replace(";", "';'");
                    URLvalues = URLvalues.Insert(0, "'");


                    //Füge ; am Ende hinzu
                    URLvalues = URLvalues.Insert(URLvalues.Length, "';");
                    
                    //System.IO.File.WriteAllText(@"D:\_dev\GDIS\temp\test.txt", URLvalues);
                    //System.IO.File.WriteAllText(@"vonGIS.txt", URLvalues);

                    //string[] createText = { "Hello", "And", "Welcome" };
                    //System.IO.File.WriteAllLines(@"vonGIS.txt", createText);
                    deprecated end*/

                    //Schreibe Array in Datei
                    if (debug) Console.WriteLine("Zeile(n) in Ausgabedatei: \n" + string.Join("\n", stringArrToWrite));
                    //leere letzte Zeile: https://stackoverflow.com/questions/11689337/net-file-writealllines-leaves-empty-line-at-the-end-of-file
                    System.IO.File.WriteAllLines(@outputPath, stringArrToWrite);
                }
                else
                {
                    if (debug) Console.WriteLine("Keine Parameter übergeben!");
                    System.IO.File.WriteAllText(@outputPath, "Keine Parameter übergeben!");
                }
            }
            else
            {
                if (debug) Console.WriteLine("Nicht über das Protokoll aufgerufen!");
                System.IO.File.WriteAllText(@outputPath, "Nicht über das Protokoll aufgerufen!");
            }
            //Warte auf Tasteneingabe (damit Konsolenoutput lesbar)
            if (debug) Console.ReadKey();
        }
    }
}