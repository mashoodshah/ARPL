using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class FocalLength : MonoBehaviour
{
    public GameObject objectMarker;
    public GameObject lensMarker;
    public GameObject imageMarker;
    public GameObject objectImage;
    //public GameObject objFlame;
    private Vector3 Pos;

    [SerializeField] private float fadePerSecond = 2.5f;

    // Start is called before the first frame update
    void Start()
    {
        //  Position
        Pos = new Vector3(0, 0, -0.48f);

        //  objectImage Material
        Material mat = objectImage.GetComponent<MeshRenderer>().material;
        //  adjust Material
        mat.SetFloat("_Mode", 2);
        mat.SetInt("_SrcBlend", (int)UnityEngine.Rendering.BlendMode.SrcAlpha);
        mat.SetInt("_DstBlend", (int)UnityEngine.Rendering.BlendMode.OneMinusSrcAlpha);
        mat.SetInt("_ZWrite", 0);
        mat.DisableKeyword("_ALPHATEST_ON");
        mat.EnableKeyword("_ALPHABLEND_ON");
        mat.DisableKeyword("_ALPHAPREMULTIPLY_ON");
        mat.renderQueue = 3000;
    }

    // Update is called once per frame
    void Update()
    {
        //if ((objectMarker.transform.position.y <= imageMarker.transform.position.y && imageMarker.transform.position.y < 0.01f) && (lensMarker.transform.position.y >= -0.01f && lensMarker.transform.position.y <= 0.01f))
        //  Check if markers are in same alignment with respect to y-axis
        if ((imageMarker.transform.position.y >= -0.02f && imageMarker.transform.position.y < 0.01f) && (lensMarker.transform.position.y >= -0.02f && lensMarker.transform.position.y <= 0.01f))
        {
            Debug.Log("Object: " + objectMarker.transform.position.z);
            Debug.Log("Lens: " + lensMarker.transform.position.z);
            Debug.Log("Image: " + imageMarker.transform.position.z);
            //  Check if markers are in same alignment with respect to z-axis
            //if ((lensMarker.transform.position.z <= 0.020f && lensMarker.transform.position.z >= 0.017f) && (imageMarker.transform.position.z <= 0.040f && imageMarker.transform.position.z >= 0.037f) && (objectMarker.transform.position.z == 0))
            if ((lensMarker.transform.position.z <= 0.040f && lensMarker.transform.position.z >= 0.020f) && (imageMarker.transform.position.z <= 0.070f && imageMarker.transform.position.z >= 0.040f) && (objectMarker.transform.position.z == 0))
            {
                //  get current position of scren object in x, y and z
                float x = transform.position.x;
                float y = transform.position.y;
                float z = transform.position.z;

                //  vector with the current position
                Vector3 temp = new Vector3(x, y, z);
                Vector3 temp1 = new Vector3(x, 0.9f, z);

                //  Display Image
                Vector3 scale = transform.localScale;
                scale.Set(0.2f, 0.1f, 1);
                transform.localScale = scale;

                //  Distance    //////////////////////////////////////////////////////////////
                //  Distance between object marker and screen object
                float distanceCI = Vector3.Distance(objectMarker.transform.position, transform.position);
                Debug.Log("Object, Image: " + distanceCI);

                //  Distance between object marker and lens marker  --imp
                float distanceOL = Vector3.Distance(objectMarker.transform.position, lensMarker.transform.position);
                Debug.Log("Object, Lens: " + distanceOL);

                //  Distance between lens marker and image marker   --imp
                float distanceLI = Vector3.Distance(lensMarker.transform.position, imageMarker.transform.position);
                Debug.Log("Lens, Image: " + distanceLI);

                //  Distance between lens marker and screen object
                float distanceLensScreen = Vector3.Distance(lensMarker.transform.position, transform.position);
                Debug.Log("Lens, Screen: " + distanceLensScreen);

                //  Distance between object marker and image marker
                float distanceOI = Vector3.Distance(objectMarker.transform.position, imageMarker.transform.position);
                Debug.Log("Object, Image: " + distanceOI);

                //Distance Conditions
                if ((distanceOL <= 0.30 && distanceOL >= 0.27) && (distanceLI <= 0.31 && distanceLI >= 0.28))
                {
                    Debug.Log("if 1!");
                    //StartCoroutine(FadeTo(1.0f, 1.0f));
                    StartCoroutine(FadeTo(0.99f, 1.0f));
                }
                else if ((distanceOL <= 0.20 && distanceOL >= 0.18) && (distanceLI <= 0.20 && distanceLI >= 0.18))
                {
                    Debug.Log("if 2!");
                    StartCoroutine(FadeTo(0.99f, 1.0f));
                    //StartCoroutine(FadeTo(1.0f, 1.0f));
                }
                else
                {
                    Debug.Log("Blur!");
                    StartCoroutine(FadeTo(0.2f, 1.0f));
                }
            }
            else
            {
                //Vector3 temp1 = new Vector3(0, 0, 0);
                //transform.position = temp1;
                //Scale
                Vector3 scale = transform.localScale;
                scale.Set(0.2f, 0.1f, 0.1f);
                transform.localScale = scale;
            }
        }
        else
        {
            //Vector3 temp1 = new Vector3(0, 0, 0);
            //transform.position = temp1;
            //Scale
            Vector3 scale = transform.localScale;
            scale.Set(0.2f, 0.1f, 0.1f);
            transform.localScale = scale;
        }
    }

    IEnumerator FadeTo(float aValue, float aTime)
    {
        float alpha = objectImage.GetComponent<Renderer>().material.color.a;
        for (float t = 0.0f; t < 1.0f; t += Time.deltaTime / aTime)
        {
            Color newColor = new Color(1, 1, 1, Mathf.Lerp(alpha, aValue, t));
            objectImage.GetComponent<Renderer>().material.color = newColor;
            yield return null;
        }
    }
}