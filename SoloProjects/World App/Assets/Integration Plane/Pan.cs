using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Pan : MonoBehaviour
{

    public int longitude;
    public int latitude;
    public string Name;
    // Use this for initialization
    void Start()
    {

    }

    // Update is called once per frame
    void Update()
    {
        if (Input.GetMouseButtonDown(0))
        {
            RaycastHit hit;
            var ray = Camera.main.ScreenPointToRay(Input.mousePosition);

            if (Physics.Raycast(ray, out hit))
            {
                if (hit.transform.name == Name)
                {
                    var plane = GameObject.Find("Plane");
                    var gm = plane.GetComponent<GoogleMap>();
                    gm.centerLocation.latitude += latitude * (20 / Mathf.Pow(2, gm.zoom));// (gm.corners.NorthEast.Latitude - gm.corners.SouthWest.Latitude)*0.1f*latitude;
                    gm.centerLocation.longitude += longitude * (20 / Mathf.Pow(2, gm.zoom));//  (gm.corners.NorthEast.Longitude - gm.corners.SouthWest.Longitude)*0.1f*longitude;
                    gm.Refresh();
                    Debug.Log("My object is clicked by mouse");
                }
            }
        }
    }

    void OnSelect()
    {
        var plane = GameObject.Find("Plane");
        var gm = plane.GetComponent<GoogleMap>();
        gm.centerLocation.latitude += latitude * (20 / Mathf.Pow(2, gm.zoom));// (gm.corners.NorthEast.Latitude - gm.corners.SouthWest.Latitude)*0.1f*latitude;
        gm.centerLocation.longitude += longitude * (20 / Mathf.Pow(2, gm.zoom));//  (gm.corners.NorthEast.Longitude - gm.corners.SouthWest.Longitude)*0.1f*longitude;
        gm.Refresh();
        Debug.Log("My object is clicked by mouse");
    }
}
