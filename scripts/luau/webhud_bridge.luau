-- Starfall RP Tool - Luau style bridge template for SL testing pipelines.
-- Mirrors LSL payload shape so behavior stays consistent.

local HttpService = {}

local API_BASE = "https://your-server.example.com/api"
local avatarUuid = ""

local function buildPayload()
    return {
        avatar_uuid = avatarUuid,
        attachments = {
            { slot = "hud", attached = true, object_uuid = "HUD_OBJECT_UUID" },
            { slot = "titler", attached = true, object_uuid = "TITLER_OBJECT_UUID" },
        },
    }
end

local function onAttach(id: string)
    avatarUuid = id
    local payload = buildPayload()
    -- Replace with SL Luau HTTP call when available in your deployment.
    HttpService:PostAsync(API_BASE .. "/hud/login", payload)
end

return {
    onAttach = onAttach,
}
