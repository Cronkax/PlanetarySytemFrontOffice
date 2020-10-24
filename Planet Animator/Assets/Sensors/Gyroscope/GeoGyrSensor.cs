using UnityEngine;
using System.Collections;

public class GeoGyrSensor : GeoSensor {
    public override bool Poll()
    {
        Input.gyro.enabled = true;
        Gyroscope lastReading = Input.gyro;
        var gyr = new GeoGyrReading
        {
            attitude = lastReading.attitude,
        };
        AddReading(gyr);
        return true;
    }

}
