<?php

namespace Model\Page;

use Model\Page\Page;

class PagePost extends Page
{
    public function __construct()
    {
        parent::__construct();
    }

    // Lấy danh sách bài viết của page, giới hạn số lượng bài trả về
    // Giúp xem các post mới nhất, phục vụ phân tích, kiểm duyệt hoặc hiển thị nội dung
    public function getPosts($pageId, $accessToken, $limit = 10)
    {
        $response = $this->client->get("https://graph.facebook.com/v19.0/{$pageId}/posts", [
            'query' => [
                'limit' => $limit,
                'access_token' => $accessToken
            ]
        ]);
        return json_decode($response->getBody(), true);
    }
    // Tạo bài viết mới trên page với nội dung text thuần
    // Dùng để đăng thông báo, tin tức, hoặc quảng bá trực tiếp trên trang
    public function createPost($pageId, $accessToken, $postContent)
    {
        $response = $this->client->post("https://graph.facebook.com/v19.0/$pageId/feed", [
            'form_params' => [
                'message' => $postContent,
                'access_token' => $accessToken,
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    // Tạo bài viết dạng ảnh trên page, kèm chú thích (caption)
    // Thường dùng cho đăng bài có hình ảnh sản phẩm, sự kiện, tạo tương tác hình ảnh đẹp
    public function createImagePost($pageId, $accessToken, array $imageUrls, $caption = '')
    {
        $mediaFbids = [];

        // Bước 1: Upload từng ảnh (ẩn)
        foreach ($imageUrls as $imageUrl) {
            $uploadResponse = $this->client->post("https://graph.facebook.com/v19.0/{$pageId}/photos", [
                'form_params' => [
                    'url' => $imageUrl,
                    'published' => false,
                    'access_token' => $accessToken
                ]
            ]);

            $uploadData = json_decode($uploadResponse->getBody(), true);

            if (isset($uploadData['id'])) {
                $mediaFbids[] = ['media_fbid' => $uploadData['id']];
            }
        }

        // Bước 2: Tạo bài viết chứa nhiều ảnh
        if (!empty($mediaFbids)) {
            $response = $this->client->post("https://graph.facebook.com/v19.0/{$pageId}/feed", [
                'form_params' => [
                    'message' => $caption,
                    'attached_media' => json_encode($mediaFbids),
                    'access_token' => $accessToken
                ]
            ]);
            return json_decode($response->getBody(), true); // Id là post_id, ID của bài viết
        }
        return null;
    }

    // Cập nhật nội dung bài viết đã có, chỉ chỉnh sửa phần text
    // Dùng khi cần chỉnh sửa lỗi chính tả, bổ sung thông tin cho bài viết đã đăng
    public function updatePost($postId, $accessToken, $updateMessage)
    {
        $response = $this->client->post("https://graph.facebook.com/v19.0/{$postId}", [
            'form_params' => [
                'message' => $updateMessage,
                'access_token' => $accessToken
            ]
        ]);
        return json_decode($response->getBody(), true); // Trả về success => true
    }

    // Xóa bài viết theo postId, giúp quản lý nội dung, loại bỏ bài không phù hợp
    public function deletePost($postId, $accessToken)
    {
        $response = $this->client->delete("https://graph.facebook.com/v19.0/{$postId}", [
            'query' => [
                'access_token' => $accessToken
            ]
        ]);
        return json_decode($response->getBody(), true); // Trả về success => true
    }

    // Lấy danh sách bình luận trên một bài viết
    // Giúp theo dõi ý kiến khách hàng, phản hồi, phục vụ chăm sóc hoặc xử lý phản hồi
    public function getPostComment($postId, $accessToken)
    {
        $response = $this->client->get("https://graph.facebook.com/v19.0/$postId/comments", [
            'query' => [
                'access_token' => $accessToken
            ]
        ]);
        return json_decode($response->getBody(), true); // Trả một mảng mix thông tin comment (có comment Id nữa)
    }

    // Trả lời bình luận của người dùng trên page với nội dung cố định "Cảm ơn bạn!"
    // Dùng để tự động phản hồi, giữ tương tác khách hàng, tăng độ thân thiện trang
    public function replyComment($commentId, $accessToken, $yourReply)
    {
        $response = $this->client->post("https://graph.facebook.com/v19.0/{$commentId}/comments", [
            'form_params' => [
                'message' => $yourReply,
                'access_token' => $accessToken
            ]
        ]);
        return json_decode($response->getBody(), true); // Trả về id của comment bạn vừa reply
    }

    // Ẩn bình luận trên bài viết, không cho người khác thấy nhưng vẫn tồn tại
    // Hữu ích khi xử lý bình luận tiêu cực, spam hoặc không phù hợp mà không xóa hẳn
    public function hiddenComment($commentId, $accessToken)
    {
        $response = $this->client->post("https://graph.facebook.com/v19.0/{$commentId}", [
            'form_params' => [
                'is_hidden' => 'true',
                'access_token' => $accessToken
            ]
        ]);
        return json_decode($response->getBody(), true); // Trả về succes => true/false (comment của admin/Page không thể ân vì fb đã hạn chế)
    }

    // Xóa hẳn một bình luận khỏi bài viết
    // Dùng khi bình luận vi phạm chính sách hoặc spam cần loại bỏ hoàn toàn
    public function deleteComment($commentId, $accessToken)
    {
        $response = $this->client->delete("https://graph.facebook.com/v19.0/{$commentId}", [
            'query' => [
                'access_token' => $accessToken
            ]
        ]);
        return json_decode($response->getBody(), true); // Trả về succes => true/false
    }

    // Thích (like) một bài viết hoặc bình luận
    // Giúp tăng tương tác, phản hồi tích cực từ page đến người dùng
    public function likePostOrComment($postOrCommentId, $accessToken)
    {
        $response = $this->client->post("https://graph.facebook.com/v19.0/{$postOrCommentId}/likes", [
            'form_params' => [
                'access_token' => $accessToken
            ]
        ]);
        return json_decode($response->getBody(), true);    // Trả về succes => true/false
    }

    // Bỏ thích (unlike) một bài viết hoặc bình luận
    // Dùng khi muốn điều chỉnh lại trạng thái tương tác trước đó
    public function unlikePostOrComment($postOrCommentId, $accessToken)
    {
        $response = $this->client->delete("https://graph.facebook.com/v19.0/{$postOrCommentId}/likes", [
            'query' => [
                'access_token' => $accessToken
            ]
        ]);
        return json_decode($response->getBody(), true); // Trả về succes => true/false
    }
}
