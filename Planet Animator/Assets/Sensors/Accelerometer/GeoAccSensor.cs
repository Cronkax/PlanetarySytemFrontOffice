using UnityEngine;
using System.Collections;

public class GeoAccSensor : GeoSensor {
    public override bool Poll()
    {
        Input.gyro.enabled = true;
        Vector3 lastReading = Input.gyro.userAcceleration;
        var ac = new GeoAccReading
        {
            acceleration = lastReading
        };
        AddReading(ac);
        return true;
    }

}
