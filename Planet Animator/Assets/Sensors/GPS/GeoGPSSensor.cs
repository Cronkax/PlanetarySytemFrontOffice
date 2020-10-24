using System;
using UnityEngine;

public class GeoGPSSensor : GeoSensor
{
    //Aponta para o Input.location
    private LocationService _location;

    public GeoGPSSensor()
    {
        _location = Input.location;
        _location.Start(1, 1);
    }

    public override bool Poll()
    {
        //Se o utilizador não tem o GPS ligado
        if (!_location.isEnabledByUser)
        {
            return false;
        }


        //Se o serviço de localização tiver falhado
        if (_location.status == LocationServiceStatus.Failed)
        {
            _location.Start(1, 1);
            return false;
        }

        //Se o serviço de localização estiver a correr
        if (_location.status == LocationServiceStatus.Running)
        {
            //Cria um novo GPS reading
            var red = new GeoGPSReading
            {
                altitude = _location.lastData.altitude,
                latitude = _location.lastData.latitude,
                longitude = _location.lastData.longitude,
            };

            //última leitura do GPS
            var lastReading = (GeoGPSReading) GetLastReading();

            //Se já existe uma reading
            if(lastReading != null)
            {
                //Timestamp da nova leitura - Timestamp da última leitura
                var deltaTime = (red.timestamp.Subtract(new DateTime(1970, 1, 1)).TotalSeconds - lastReading.timestamp.Subtract(new DateTime(1970, 1, 1)).TotalSeconds);
                var lastConverter = new GeoUTMConverter();
                //Converte a latitude e a longitude da última leitura para um sistema de coordenadas x, y
                lastConverter.ToUTM(lastReading.latitude, lastReading.longitude);
                var redConverter = new GeoUTMConverter();
                //Converte a latitude e a longitude da nova leitura para um sistema de coordenadas x, y
                redConverter.ToUTM(red.latitude, red.longitude);

                //Calcula a distância entre a última leitura e a nova
                var distance = Vector2.Distance(new Vector2((float) lastConverter.X, (float) lastConverter.Y),
                    new Vector2((float)redConverter.X, (float)redConverter.Y));
                //Calcula a velocidade entre a última leitura e a nova
                var speed = distance / deltaTime;
                red.distance = distance;
                red.speed = (float) speed;
                red.heading = new Vector2((float)(redConverter.X - lastConverter.X), (float)(redConverter.Y - lastConverter.Y));
            }

            Debug.LogError("GPS Reading Added " + red.ToString());
            AddReading(red);
            return true;

        }
        //Se o serviço de localização falhou
        else
        { 
            return false;
        }
    }

    public override void Stop()
    {
        if(_location != null)
            _location.Stop();
    }

    public override SensorType Type
    {
        get { return SensorType.GPS; }
    }
}
