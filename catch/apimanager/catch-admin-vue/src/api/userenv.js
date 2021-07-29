import request from "@/utils/request";
export function userenvList() {
  return request({
    url: "/apiTesterUserenv",
    method: "get"
  });
}
