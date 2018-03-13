using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Click : MonoBehaviour
{

    public string Name;
    public int modifier;
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
                    gm.zoom += modifier;
                    gm.mapType = gm.zoom > 10 ? GoogleMap.MapType.Hybrid : GoogleMap.MapType.Satellite;
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
        gm.zoom += modifier;
        gm.mapType = gm.zoom > 10 ? GoogleMap.MapType.Hybrid : GoogleMap.MapType.Satellite;
        gm.Refresh();
        
    }
}
