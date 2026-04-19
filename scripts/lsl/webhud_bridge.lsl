// Starfall RP Tool - LSL HUD bridge template
// Sends attach/login payloads to the web framework.

string API_BASE = "https://your-server.example.com/api";
string AVATAR_UUID = "";
key request_id;

string build_payload()
{
    return llList2Json(JSON_OBJECT, [
        "avatar_uuid", AVATAR_UUID,
        "attachments", llList2Json(JSON_ARRAY, [
            llList2Json(JSON_OBJECT, ["slot", "hud", "attached", TRUE, "object_uuid", (string)llGetKey()]),
            llList2Json(JSON_OBJECT, ["slot", "titler", "attached", TRUE, "object_uuid", ""])
        ])
    ]);
}

default
{
    attach(key id)
    {
        if (id)
        {
            AVATAR_UUID = (string)id;
            request_id = llHTTPRequest(
                API_BASE + "/hud/login",
                [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/json"],
                build_payload()
            );
        }
    }

    http_response(key request, integer status, list metadata, string body)
    {
        if (request == request_id && status == 200)
        {
            llOwnerSay("HUD login success.");
        }
    }
}
